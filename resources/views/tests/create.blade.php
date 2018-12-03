@extends('lms')

@section('content')
    <tests-form base-url="{{ url()->to('/tests') }}"
                csrf="{{ csrf_token() }}"
                :errors="{{ json_encode($errors->messages()) }}"
                message="{{ session('message') }}"
                :default="{{ json_encode(old()) }}"
                :test="{}"></tests-form>
@endsection