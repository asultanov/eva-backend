let $table = $('#bootstrap_tbl');
let $sendMessage = $('#send-message');
let selections = []

function getIdSelections() {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
        return row.id
    })
}

$('#userrole').select2({containerCssClass: 'select-sm', dropdownParent: $('#user_modal')});

$(document).on('click', '#add_user', function () {
    $('.ajax-post-form')[1].reset();
    $('#user_id').val('');
    $('#user_modal').modal('show');
});

function deleteIds(id) {
    confirm("Вы уверены что хотите выполнить это действие?") && $.post(routes["delete-user"], {
        _token: token,
        id: id
    }, function (data) {
        "success" == data.status
            ? (toastr.success(data.message, "Успешно", {closeButton: true}),
                $table.bootstrapTable("remove", {field: "id", values: [id]}))
            : toastr.error(data.message, "Ошибка.")
    })
}

$(document).on('submit', '.ajax-post-form', function () {
    console.log(this)
    let fd = new FormData(this);
    fd.append('isd', JSON.stringify(getIdSelections()))
    return $.ajax({
        url: $(this).attr('action'),
        method: "POST",
        crossDomain: true,
        dataType: 'json',
        timeout: 5 * 60 * 1000,
        processData: false,
        contentType: false,
        data: fd,
        success: function (data) {
            if (data.status == 'success') {
                toastr.success(data.message, "Успешно", {closeButton: true});
                $(".modal").modal("hide");
                $table.bootstrapTable("refresh");
            } else
                toastr.error(data.message.join('<hr><br>'), "Ошибка.");
        },
    }), false;
});

function operateFormatter(value, row) {
    return ['<div class="btn-group">',
        '<a title="Вход от имени" href="' + routes["spec-auth"] + '/' + row.id + '" class="btn btn-sm btn-outline-info"><i class="fas fa-unlock-alt"></i> </a>',
        '<button type="button" title="Быстрое редактирование" class="fast_edit btn btn-sm btn-outline-info"><i class="fas fa-pencil-alt"></i> </button>',
        '<button type="button" title="Удалить" class="remove btn btn-sm btn-outline-info"><i class="fas fa-trash-alt"></i> </button>',
        '</div>'].join('')
}

function roleFormatter(value, row) {
    return [row.roles.map(function (arr) {
        return arr.text;
    })].join(',');
}

window.operateEvents = {
    'click .fast_edit': function (e, value, row, index) {
        $('#user_modal form')[0].reset();
        $('#user_id').val(row.id);
        $('#name').val(row.name)
        $('#last_name').val(row.last_name)
        $('#patronymic').val(row.patronymic)
        $('#birthday').val(row.birthday)
        $('#phone').val(row.phone)
        $('#email').val(row.email)
        $('#diagnosis option[value=' + row.diagnosis?.id + ']').prop('selected', true);
        $('#therapy option[value=' + row.therapy?.id + ']').prop('selected', true);
        $('#userrole').select2({placeholder: 'Роль', containerCssClass: 'select-sm', dropdownParent: $('#user_modal')}).val(row.roles.map(
            function (arr) {
                return arr.id
            })).trigger('change');
        $('#user_modal').modal('show');
    },
    'click .remove': function (e, value, row) {
        deleteIds(row.id);
    }
}

$table.bootstrapTable({
    onDblClickRow: function (row) {
        window.open(routes['user-data'].replace(':id', row.id), '_blank');
    },
    columns: [
        {checkbox: true, align: 'center'},
        {field: 'id', title: '#', align: 'center', visible: false},
        {title: 'Управление', align: "center", clickToSelect: false, events: window.operateEvents, width: 150, formatter: operateFormatter},
        {field: 'name', title: 'name', align: 'center'},
        {field: 'last_name', title: 'last_name', align: 'center'},
        {field: 'patronymic', title: 'patronymic', align: 'center'},
        {field: 'birthday', title: 'birthday', align: 'center'},
        {field: 'phone', title: 'phone', align: 'center'},
        {field: 'email', title: 'email', align: 'center'},
        {field: 'diagnosis.name', title: 'Диагноз', align: 'center'},
        {field: 'therapy.name', title: 'Терапия', align: 'center'},
        {title: 'Роль', align: 'center', formatter: roleFormatter},
    ]
}).on('check.bs.table uncheck.bs.table ' +
    'check-all.bs.table uncheck-all.bs.table',
    function () {
        $sendMessage.prop('disabled', !$table.bootstrapTable('getSelections').length)
        selections = getIdSelections();
    });

$(document).on('click', '#reset-filter', function () {
    $.get(routes["clear-filter"], {type: "users"})
        .done(function () {
            $('.ajax-post-form')[0].reset();
            $table.bootstrapTable("refresh");
        });
});
