@extends('lms')

@section('content')
    <tests-form url="{{ url()->to('/tests') }}"
                csrf="{{ csrf_token() }}"
                :errors="{{ json_encode($errors->messages()) }}"
                :default="{{ json_encode(old()) }}"></tests-form>
@endsection