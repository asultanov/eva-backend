@extends('layouts.layoutMaster')

@section('title', 'Управление пользователями')

@section('page-style')
    @vite([
            'resources/assets/vendor/libs/toastr/toastr.scss',
            'resources/assets/vendor/libs/bootstrap-table/bootstrap-table.scss',
            'resources/assets/vendor/libs/bootstrap-icons/bootstrap-icons.scss',
            'resources/assets/vendor/libs/select2/select2.scss',
          ])
@endsection

@section('page-script')
    @vite([
            'resources/assets/js/phone-input.js',
            'resources/assets/vendor/libs/bootstrap-table/bootstrap-table.js',
            'resources/assets/vendor/libs/toastr/toastr.js',
            'resources/assets/vendor/libs/select2/select2.js',
            'resources/assets/js/users.js',
          ])
    <script>
        let token = '{{ csrf_token() }}';
        let routes = {
            'delete-user': '{{ route('admin.deleteUser') }}',
            'clear-filter': '{{ route('admin.clearFilter') }}',
            'spec-auth': '{{ route('admin.specAuth') }}',
            'user-data': '{{ route('admin.userData', ':id') }}'
        };
    </script>
@endsection

@section('modals')
    <div class="offcanvas offcanvas-end" {{-- data-bs-scroll="true" data-bs-backdrop="false" --}} tabindex="-1" id="offcanvasFilter"
         aria-labelledby="offcanvasFilterLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasFilterLabel" class="offcanvas-title">Фильтр</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form method="POST" class="ajax-post-form" action="{{ route('admin.saveFilter') }}">
                <input type="hidden" name="type" value="users">

                <div class="mb-1">
                    <label class="form-label" for="f-last-name">Фамилия</label>
                    <input id="f-last-name" name="last_name" value="{{ $data['last_name'] ?? '' }}" class="form-control form-control-sm" type="text" placeholder="Фамилия">
                </div>

                <div class="mb-1">
                    <label class="form-label" for="f-name">Имя</label>
                    <input id="f-name" name="name" value="{{ $data['name'] ?? '' }}" class="form-control form-control-sm" type="text" placeholder="Имя">
                </div>

                <div class="mb-1">
                    <label class="form-label" for="f-patronymic">Отчество</label>
                    <input id="f-patronymic" name="patronymic" value="{{ $data['patronymic'] ?? '' }}" class="form-control form-control-sm" type="text" placeholder="Отчество">
                </div>

                <div class="mb-25">
                    <label class="form-label mt-1">Дата рождения</label>
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text"> от</span>
                        <input type="date" value="{{ $data['from_date'] ?? '' }}" name="from_date" class="form-control form-control-sm"/>
                        <span class="input-group-text"> до </span>
                        <input type="date" value="{{ $data['to_date'] ?? '' }}" name="to_date" class="form-control form-control-sm"/>
                    </div>
                </div>

                <div class="mb-1">
                    <label for="role" class="form-label d-block">Роль</label>
                    <select class="form-select form-select-sm" id="role" name="role">
                        <option value="">Выбрать</option>

                        @foreach($roles as $role)
                            <option value="{{ $role->id }}"{{ selopt($role->id,  $data['role'] ?? '') }}>{{ $role->name_ru }}</option>
                        @endforeach
                    </select>
                </div>


                <button type="submit" class="btn btn-primary btn-sm mb-1 w-100"><i class="fas fa-search"></i> Отфильтровать</button>
                <button type="button" id="reset-filter" class="btn btn-danger btn-sm mb-1 w-100"><i class="fas fa-trash"></i> Очистить</button>
                <button type="button" class="btn btn-outline-secondary btn-sm d-grid w-100" data-bs-dismiss="offcanvas">Закрыть</button>
            </form>
        </div>
    </div>

    {{-- Edit User Modal --}}
    <div class="modal fade" id="user_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ajax-post-form" method="POST" action="{{ route('admin.fastEdit') }}">
                        @csrf
                        <input type="hidden" name="id" id="user_id">
                        <div class="row mb-1">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="name">Имя</label>
                                <input type="text" id="name" name="name" class="form-control form-control-sm" placeholder="Имя">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="last_name">Фамилия</label>
                                <input type="text" id="last_name" name="last_name" class="form-control form-control-sm" placeholder="Фамилия">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="patronymic">Отчество</label>
                                <input type="text" id="patronymic" name="patronymic" class="form-control form-control-sm" placeholder="Отчество">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="birthday">Дата рождения</label>
                                <input type="date" id="birthday" name="birthday" class="form-control form-control-sm" placeholder="Дата рождения">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="email">e-mail</label>
                                <input type="text" id="email" name="email" class="form-control form-control-sm" placeholder="e-mail">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="phone">Телефон</label>
                                <input class="form-control form-control-sm" id="phone" type="tel" data-tel-input placeholder="+7 (999) 999-99-99" maxlength="18" name="phone" autofocus/>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="diagnosis">Диагноз</label>
                                <select class="form-select form-select-sm" name="diagnosis_id" id="diagnosis">
                                    <option value="">Выбрать</option>
                                    @foreach(\App\Models\Guides\Diagnosis::all() as $diagnosis)
                                        <option value="{{ $diagnosis->id }}">{{ $diagnosis->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="therapy">Терапия</label>
                                <select class="form-select form-select-sm" name="therapy_id" id="therapy">
                                    <option value="">Выбрать</option>
                                    @foreach(\App\Models\Guides\Therapy::all() as $therapy)
                                        <option value="{{ $therapy->id }}">{{ $therapy->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-1" id="userrole2">
                            <div class="col-12">
                                <label class="form-label" for="userrole">Роли</label>
                                <select class="form-control form-control-sm" multiple="multiple" name="userrole[]" id="userrole">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name_ru }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-center mt-2 pt-50">
                                <button type="submit" class="btn btn-sm btn-primary me-1">Сохранить</button>
                                <button type="reset" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal"
                                        aria-label="Close">
                                    Закрыть
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--/ Edit User Modal --}}

    <div class="modal fade" id="sendMessageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ajax-post-form" method="POST" action="{{-- route('createTask') --}}">
                        @csrf
                        <div class="mb-3">
                            <label for="message" class="form-label">Сообщение</label>
                            <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">Картинка</label>
                            <input class="form-control form-control-sm" type="file" id="file" name="file">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-sm btn-primary me-1">Создать рассылку</button>
                            <button type="reset" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                Закрыть
                            </button>
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
                    <h4 class="card-title m-0">Управление пользователями</h4>
                    <div class="dt-action-buttons text-end">
                        <button id="send-message" type="button" class="btn btn-primary btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#sendMessageModal" disabled>
                            <i class="fab fa-telegram me-2"></i>
                            Отправить сообщение
                        </button>
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilter" aria-controls="offcanvasFilter">
                            <i class="fas fa-search me-2"></i>
                            Фильтр
                        </button>
                        <button id="add_user" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-square me-2"></i> Добавить нового пользователя
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="bootstrap_tbl"
                           data-classes="table table-hover table-bordered table-sm"
                           data-search="false"
                           data-page-size="25"
                           data-show-refresh="true"
                           data-show-toggle="true"
                           data-show-columns="true"
                           data-show-columns-toggle-all="true"
                           data-pagination="true"
                           data-id-field="id"
                           data-side-pagination="server"
                           data-page-list="[10, 25, 50, 100, all]"
                           data-url="{{ route('admin.allUsers') }}">
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection





