@extends('lms')

@section('content')
    <tests-form base-url="{{ url('tests') }}"
                csrf="{{ csrf_token() }}"
                :errors="{{ json_encode($errors->messages()) }}"
                message="{{ session('message') }}"
                :default="{{ json_encode(old()) }}"
                :test="{{ json_encode($test) }}"></tests-form>
    <questions-index :questions="{{ json_encode($questions) }}"
                     base-url="{{ url('/') }}"
                     test-id="{{ isset($test['id']) ? $test['id'] : 0 }}"></questions-index>
@endsection