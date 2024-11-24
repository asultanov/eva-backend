@extends('layouts.layoutMaster')

@section('title', 'Управление ролями пользователей')


{{--@section('vendor-script')--}}
{{--    @vite(['resources/assets/vendor/libs/jquery/jquery.js'])--}}
{{--@endsection--}}

@section('page-script')

    @vite([
    'resources/assets/vendor/libs/bootstrap-table/bootstrap-table.js',
    'resources/assets/vendor/libs/toastr/toastr.js',
    'resources/assets/js/roles.js'
          ])
    <script>
            let token = '{{ csrf_token() }}', selections = [];
            let routes = {'delete-role': '{{ route('removeRole') }}'};
    </script>
@endsection

@section('page-style')
    @vite([
    'resources/assets/vendor/libs/toastr/toastr.scss',
    'resources/assets/vendor/libs/bootstrap-table/bootstrap-table.scss',
    'resources/assets/vendor/libs/bootstrap-icons/bootstrap-icons.scss',
          ])
@endsection


@section('modals')
    <div class="modal fade" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <h5 class="modal-title">Роли пользователей </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form id="editform" class="row" method="POST" action="{{ route('editRole') }}">
                        @csrf
                        <input type="hidden" name="id" id="role_id">
                        <div class="col-12">
                            <label for="name_ru">Роль</label>
                            <input name="name_ru" id="name_ru" type="text" class="form-control form-control-sm" placeholder="Роль" required>
                        </div>
                        <div class="col-12 text-end mt-4">
                            <button type="reset" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal" aria-label="Закрыть">Отмена</button>
                            <button type="submit" class="btn btn-success btn-sm me-sm-3 me-1">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0">Управление ролями пользователей</h4>
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons d-inline-flex">
                            <button id="command" class="btn btn-sm btn-success"><i class="fas fa-plus-square me-2"></i> Добавить новую роль</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table id="bootstrap_tbl"
                           data-classes="table table-hover table-bordered table-sm"
                           data-search="true"
                           data-show-refresh="true"
                           data-show-toggle="true"
                           data-show-columns="true"
                           data-show-columns-toggle-all="true"
                           data-pagination="true"
                           data-id-field="id"
                           data-page-list="[10, 25, 50, 100, all]"
                           data-url="{{ route('getRoles') }}">
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
