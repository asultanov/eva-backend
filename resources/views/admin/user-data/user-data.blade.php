@extends('layouts/layoutMaster')

@section('title', 'Главная')
@section('page-style')
@endsection

@section('page-script')
    @vite([

    ])
@endsection

@section('content')
    <div class="card mb-6">
        <div class="card-body pt-12">
            <div class="row">
                <div class="col-6">
                    <div class="user-avatar-section">
                        <div class="d-flex align-items-center flex-column">
                            <img class="img-fluid rounded mb-4" src="../../assets/img/avatars/1.png" height="120" width="120" alt="User avatar">
                            <div class="user-info text-center">
                                <h5>{{ $user->full_name }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <h5 class="pb-4 border-bottom mb-4">Детали</h5>
                    <div class="info-container">
                        <ul class="list-unstyled mb-6">
                            <li class="mb-2">
                                <span class="h6">Фамилия:</span>
                                <span>{{ $user->last_name }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Имя:</span>
                                <span>{{ $user->name }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Отчество:</span>
                                <span>{{ $user->patronymic }}</span>
                            </li>

                            <li class="mb-2">
                                <span class="h6">Дата рождения:</span>
                                <span>{{ $user->birthday?->format('d.m.Y') }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Телефон:</span>
                                <span>{{ phoneFormatter($user->phone) }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">E-mail:</span>
                                <span>{{ $user->email }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Диагноз:</span>
                                <span>{{ $user->diagnosis?->name }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Терапия:</span>
                                <span>{{ $user->therapy?->name }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-2">
        <h5 class="card-header">Прием препарата</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>Дата получения препарата</th>
                    <th>Наименование препарата</th>
                    <th>Доза препарата</th>
                    <th>Время получения терапии</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($user->gettingTherapies as $item)
                    <tr>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->therapy?->name }}</td>
                        <td>{{ $item->dose_mg }}</td>
                        <td>{{ $item->time }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-2">
        <h5 class="card-header">Общий анализ крови</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>ОАК WBC<br>(лейкоциты)</th>
                    <th>RBC<br>(эритроциты)</th>
                    <th>PLT<br>(тромбоциты)</th>
                    <th>Hb<br>(гемоглобин)</th>
                    <th>Ht<br>(гематокрит)</th>
                    <th>Палочкоядерные</th>
                    <th>Сегментоядерные</th>
                    <th>Лимфоциты</th>
                    <th>Моноциты</th>
                    <th>Эозинофилы</th>
                    <th>Базофилы</th>
                    <th>СОЭ</th>
                </tr>
                <tbody class="table-border-bottom-0">
                @foreach($user->bloodCount as $item)
                    <tr>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->wbc }}</td>
                        <td>{{ $item->rbc }}</td>
                        <td>{{ $item->plt }}</td>
                        <td>{{ $item->hb }}</td>
                        <td>{{ $item->ht }}</td>
                        <td>{{ $item->stab_neutrophils }}</td>
                        <td>{{ $item->seg_neutrophils }}</td>
                        <td>{{ $item->lymphocytes }}</td>
                        <td>{{ $item->monocytes }}</td>
                        <td>{{ $item->eosinophils }}</td>
                        <td>{{ $item->basophils }}</td>
                        <td>{{ $item->esr }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-2">
        <h5 class="card-header">Биохимия</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>Общий белок</th>
                    <th>Общий билирубин</th>
                    <th>Прямой билирубин</th>
                    <th>Не прямой билирубин</th>
                    <th>Мочевина</th>
                    <th>Креатинин</th>
                    <th>АЛТ</th>
                    <th>АСТ</th>
                    <th>Глюкоза</th>
                    <th>Холестерин</th>
                    <th>Мочевая кислота</th>
                    <th>Креатинин</th>
                    <th>Мочевина</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($user->bloodTest as $item)
                    <tr>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->user_id }}</td>
                        <td>{{ $item->total_protein }}</td>
                        <td>{{ $item->total_bilirubin }}</td>
                        <td>{{ $item->direct_bilirubin }}</td>
                        <td>{{ $item->indirect_bilirubin }}</td>
                        <td>{{ $item->urea }}</td>
                        <td>{{ $item->creatinine }}</td>
                        <td>{{ $item->alt }}</td>
                        <td>{{ $item->ast }}</td>
                        <td>{{ $item->glucose }}</td>
                        <td>{{ $item->cholesterol }}</td>
                        <td>{{ $item->uric_acid }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
