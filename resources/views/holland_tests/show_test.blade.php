@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="align-center">
            <h1>Mulai Tes Sekarang!</h1>
        </div>
        {{ Form::open(array('route' => 'holland_tests.store_user_test')) }}
            {{ Form::token() }}
            @foreach( $questions as $question )
                <h3>{{ $question->question }}</h3>
                <input type="hidden" name="questions_id[]" value="{{ $question->id }}">
                <div class="row">
                    <div class="btn-group btn-group-lg btn-group-toggle" data-toggle="buttons">
                        @foreach( $options as $option )
                            <label class="btn btn-lg btn-secondary" onclick="showCheck({{ $option->id }})">
                                {{ Form::radio('option_id', $option->id, true, ['autocomplete' => 'off']) }}
                                <i class="fa fa-check" aria-hidden="true" id="check-{{$option->id}}" style="visibility: hidden;"></i>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
            @if( Request::is($questions->url($questions->lastPage())) )
                {{ Form::submit('Submit!') }}
            @endif
            {{ $questions->links() }}
        {{ Form::close() }}
    </div>
@endsection

@push('body')
    <script>
        function showCheck(id) {
            document.getElementById('check-' + id).style.visibility = 'visible';
        }
    </script>
@endpush
