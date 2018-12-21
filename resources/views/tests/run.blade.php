@extends('lms')

@section('content')
    @if($test)
    <test-run :test="{{ json_encode($test) }}"></test-run>
    @else
    <b-container>
        <b-card header="Результаты">
            <h2>Тест завершён!</h2>
            <p>Результаты будут опубликованы в течении суток</p>
        </b-card>
    </b-container>
    @endif
@endsection