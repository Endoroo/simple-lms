@extends('lms')

@section('content')
    <tests-index base-url="{{ url('/') }}"
                 message="{{ session('message') }}"
                 :tests="{{ json_encode($tests) }}"
                 :question-types="{{ json_encode($questionTypes) }}"
                 title="{{ $title }}"
                 add="{{ $add }}"
                 :fields="{{ json_encode($fields) }}"></tests-index>
@endsection