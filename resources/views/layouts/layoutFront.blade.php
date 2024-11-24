@php
$configData = PageConfig::appClasses();
$isFront = true;
@endphp

@section('layoutContent')

@extends('layouts/commonMaster' )

@include('layouts/sections/navbar/navbar-front')
{{-- Include Breadcrumb --}}
@if($configData['pageHeader'] == true && isset($configData['pageHeader']))
    @include('layouts.sections.navbar.breadcrumb')
@endif
<!-- Sections:Start -->
@yield('content')
<!-- / Sections:End -->

@include('layouts/sections/footer/footer-front')
@endsection
