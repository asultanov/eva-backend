@extends('layouts.layoutMaster')

@section('title', 'Управление привилегиями')

@section('page-script')
    @vite(['resources/assets/js/permissions.js'])
    <script>
        let privs = {!! $roles !!};
    </script>
@endsection

@section('modals')
    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
            <div class="modal-content p-3 p-md-5">
                <button
                    type="button"
                    class="btn-close btn-pinned"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <p class="text-muted">Установка разрешений для ролей</p>
                    </div>
                    <!-- Add role form -->
                    <form action="{{ route('changePerms') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="role_id">
                        <div class="col-12">
                            <!-- Permission table -->
                            <div class="table-responsive">
                                <table class="table table-flush-spacing">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll"/>
                                                <label class="form-check-label" for="selectAll">Выбрать всё</label>
                                            </div>
                                        </td>
                                        <td class="text-nowrap fw-medium">
                                            Полный доступ
                                            <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                                               title="При выботе всех параметров владелец роли получит полный доступ к системе"></i>
                                        </td>
                                    </tr>
                                    @foreach($priv as $group)
                                        <tr>
                                            <td colspan="2" class="text-primary fw-bolder">{{$group->first()->section}}</td>
                                        </tr>
                                        @foreach($group as $val)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input value="{{ $val->id }}" name="priv[]" id="priv{{ $val->id }}"
                                                               class="priv form-check-input" type="checkbox"/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <label class="form-check-label" for="priv{{ $val->id }}"> {!!$val->description_ru !!}</label>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Permission table -->
                        </div>
                        <div class="col-12 text-end mt-4">
                            <button type="reset" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal" aria-label="Закрыть">Отмена</button>
                            <button type="submit" class="btn btn-success btn-sm me-sm-3 me-1">Сохранить</button>
                        </div>
                    </form>
                    <!--/ Add role form -->
                </div>
            </div>
        </div>
    </div>
    <!--/ Add Role Modal -->
@endsection

@section('content')
    <h3>Привилегии ролей пользователей</h3>
    <div class="row">
        @foreach($roles as $role)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <span>Разрешений всего: {{ $role->perms->count() }}</span>
                            <div class="d-flex justify-content-between align-items-end mt-1 pt-25">
                                <div class="role-heading">
                                    <h4 class="fw-bolder">{{ $role->name_ru }}</h4>
                                    <a href="javascript:void(0);" class="role-edit-modal fw-bolder" data-id="{{ $role->id }}" data-bs-toggle="modal"
                                       data-bs-target="#addRoleModal"> Редактировать привилегии </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
