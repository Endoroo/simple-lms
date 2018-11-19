@extends('lms')

@section('content')
    <tests-form url="{{ url()->to('/tests') }}"
                csrf="{{ csrf_token() }}" errors="{{ $errors->messages() }}"></tests-form>
@endsection