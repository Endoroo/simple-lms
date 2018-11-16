@extends('lms')

@section('content')
    <tests-form url="{{ url()->to('/tests') }}"></tests-form>
@endsection