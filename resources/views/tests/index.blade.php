@extends('lms')

@section('content')
    <tests-index add-link="{{ url('tests/create') }}"
                 message="{{ session('message') }}"
                 :question-types="{{ json_encode($questionTypes) }}"></tests-index>
@endsection