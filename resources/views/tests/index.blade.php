@extends('lms')

@section('content')
    <tests-index add-link="{{ url('tests/create') }}"
                 message="{{ session('message') }}"></tests-index>
@endsection