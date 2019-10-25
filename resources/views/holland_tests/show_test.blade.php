@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="align-center">
            <h1>Mulai Tes Sekarang!</h1>
        </div>
        @foreach( $questions as $question )
            <h3>{{ $question->question }}</h3>
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            @foreach( $options as $option )
                <input type="checkbox" name="options[]" value="{{ $option->id }}"> <label>{{ $option->option }}</label>
            @endforeach
        @endforeach
    </div>
@endsection()
