@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="align-center">
            <h1>Mulai Tes Sekarang!</h1>
        </div>
        {{ Form::open(array('route' => 'holland_tests.store_user_test')) }}
        @foreach( $questions as $question )
            <h3>{{ $question->question }}</h3>
            <input type="hidden" name="questions[]" value="{{ $question->id }}">
            <div class="row">
                @foreach( $options as $option )
                    <div class="col-md-3">
                        {{ Form::checkbox('options[]', $option->id, array('class' => 'custom-control-input')) }}
                    </div>
                @endforeach
            </div>
        @endforeach
        {{ Form::close }}
    </div>
@endsection()
