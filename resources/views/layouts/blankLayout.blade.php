@isset($pageConfigs)
{!! PageConfig::updatePageConfig($pageConfigs) !!}
@endisset
@php
$configData = PageConfig::appClasses();

/* Display elements */
$customizerHidden = ($customizerHidden ?? '');

@endphp

@extends('layouts/commonMaster' )

@section('layoutContent')
{{-- Include Breadcrumb --}}
@if($configData['pageHeader'] == true && isset($configData['pageHeader']))
    @include('layouts.sections.navbar.breadcrumb')
@endif
<!-- Content -->
@yield('content')
<!--/ Content -->

@endsection
