@extends('layouts.layoutMaster')

@section('title', 'Терапия')

@section('page-style')
    @vite([
            'resources/assets/vendor/libs/toastr/toastr.scss',
            'resources/assets/vendor/libs/bootstrap-table/bootstrap-table.scss',
            'resources/assets/vendor/libs/bootstrap-icons/bootstrap-icons.scss',
          ])
@endsection

@section('page-script')
    @vite([
            'resources/assets/vendor/libs/bootstrap-table/bootstrap-table.js',
            'resources/assets/vendor/libs/toastr/toastr.js',
          ])
    <script type="module">
        let selections = [];
        let routes = {'delete-diagnoses': '{{ route('guides-removeTherapy') }}'};
        let $table = $("#bootstrap_tbl");

        function getIdSelections() {
            return $.map($table.bootstrapTable("getSelections"), function (e) {
                return e.id
            })
        }

        function deleteIds(ids) {
            confirm("Вы уверены что хотите выполнить это действие?") && $.post(routes["delete-diagnoses"], {
                ids: ids
            }, function (data) {
                "success" == data.status
                    ? (toastr.success(data.message.join('<hr><br>'), "Успешно", {closeButton: !0}),
                        $("#updateGuide").modal("hide"),
                        $table.bootstrapTable("remove", {field: "id", values: ids}))
                    : toastr.error(data.message.join('<hr><br>'), "Ошибка.")
            })
        }

        function operateFormatter(value, row, index) {
            return ['<div class="btn-group">',
                '<button title="Редактировать"  type="button" class="edit btn btn-sm btn-outline-info"><i class="fas fa-pencil-alt"></i> </button>',
                '<button title="Удалить" type="button" class="remove btn btn-sm btn-outline-info"><i class="fas fa-trash-alt"></i> </button>',
                '</div>'].join('')
        };

        function initTable() {
            $table.bootstrapTable({
                columns: [
                    {field: 'name', title: 'Терапия', align: 'center', sortable: !0},
                    {title: "Action", align: "center", field: "operate", clickToSelect: !1, events: window.operateEvents, formatter: operateFormatter}
                ]
            })
        }

        window.operateEvents = {
            "click .edit": function (e, value, row) {
                $("#diagnoses_id").val(row.id);
                $("#name").val(row.name);
                $("#updateGuide").modal("show");
            }, "click .remove": function (e, value, row) {
                deleteIds([row.id]);
            }
        }

        $(document).on('submit', '.ajax-post-form', function () {
            $.post($(this).attr("action"), $(this).serialize(), function (data) {
                "success" == data.status
                    ? (toastr.success(data.message, "Успешно!", {closeButton: !0}), $(".modal").modal("hide"), $table.bootstrapTable("refresh"))
                    : toastr.error(data.message.join('<hr><br>'), "Ошибка.")
            });
            return false;
        });

        $(document).on('click', '#command', function () {
            $("#diagnoses_id").val('');
            $(".ajax-post-form")[0].reset();
            $("#updateGuide").modal("show");
        });

        $(function () {
            initTable();
            $('.fixed-table-toolbar').find('button').addClass('btn-sm');
            $('.search-input').addClass('form-control-sm');
        });
    </script>
@endsection

@section('modals')
    <div class="modal fade" id="updateGuide">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="ajax-post-form" method="POST" action="{{ route('guides-editTherapy') }}">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="diagnoses_id">
                        <div class="mb-2">
                            <label class="form-label" for="name">Терапия</label>
                            <input name="name" id="name" type="text" class="form-control form-control-sm" placeholder="Терапия" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-sm btn-success">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title m-0">Справочник - терапия</h4>
                </div>
                <div class="card-body">
                    <div id="toolbar">
                        <button type="button" id="command" class="btn btn-sm btn-outline-success width-150">
                            <i class="fas fa-plus me-2"></i> Добавить терапию
                        </button>
                    </div>
                    <table id="bootstrap_tbl"
                           data-toolbar="#toolbar"
                           data-classes="table table-hover table-bordered table-sm"
                           data-search="true"
                           data-show-refresh="true"
                           data-show-toggle="true"
                           data-show-columns="true"
                           data-show-columns-toggle-all="true"
                           data-pagination="true"
                           data-id-field="id"
                           data-page-list="[10, 25, 50, 100]"
                           data-url="{{ route('guides-getTherapies') }}">
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
