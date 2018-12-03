@extends('lms')

@section('content')
    <tests-form url="{{ url()->to('/tests') }}"
                csrf="{{ csrf_token() }}"
                :errors="{{ json_encode($errors->messages()) }}"
                message="{{ session('message') }}"
                :default="{{ json_encode(old()) }}"
                :test="{{ json_encode($test) }}"></tests-form>
    <questions-index :questions="{{ json_encode($questions) }}"></questions-index>
@endsection