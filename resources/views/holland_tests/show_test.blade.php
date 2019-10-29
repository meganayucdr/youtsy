@extends('layouts.app')

@push('head')
    <style>
        .btn.active span.fa {
            visibility: visible;
        }

        .btn span.fa {
            visibility: hidden;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="align-center">
            <h1>Mulai Tes Sekarang!</h1>
        </div>
        {{ Form::open(array('route' => 'holland_tests.store_user_test')) }}

            @foreach( $questions as $question )
                <h3>{{ $question->question }}</h3>
                <input type="hidden" name="questions_id[]" value="{{ $question->id }}">
                <div class="row">
                    <div class="btn-group btn-group-lg btn-group-toggle" data-toggle="buttons">
                        @foreach( $options as $option )
                            <label class="btn btn-lg btn-secondary" onclick="showCheck({{ $option->id }})">
                                {{ Form::radio('option_id', $option->id, true, ['autocomplete' => 'off']) }}
                                <span class="fa fa-check" aria-hidden="true" style="visibility: hidden"></span>
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
    <script type="text/javascript">
        function showCheck(id) {
            if (document.getElementById('label-' + id).button('toggle') === true)
                document.getElementById('check-' + id).style.visibility = 'visible';
        }
    </script>
@endsection


