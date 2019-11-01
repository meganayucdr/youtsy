@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="align-center py-5">
            <h1 class="text-center">Mulai Tes Sekarang!</h1>
        </div>
        {{ Session::push('test.question', []) }}
        {{ Session::push('test.answer', []) }}
        {{ Form::open(array('route' => 'holland_tests.store_user_test')) }}
            {{ csrf_field() }}
            @foreach( $questions as $question )
                <div class="py-4">
                    <h3 class="text-center">{{ $question->question }}</h3>
                    <input type="hidden" name="questions_id[]" value="{{ $question->id }}" class="form-control">
                    {{ Session::push('test.question', $question->id) }}
                    <div class="row justify-content-center">
                        <div class="col-2">
                            <p class="text-left text-md-right">Sangat Tidak Setuju</p>
                        </div>
                        <div class="col-2 text-center">
                        @foreach( $options as $option )
                            <label class="container-radio">
                                {{ Form::radio('options_id['. $question->id . ']', $option->id, false) }}
                                <span class="checkmark"></span>
                            </label>
                                {{ Session::push('test.question', $options_id[$question->id]) }}
                        @endforeach
                        </div>
                        <div class="col-2">
                            <p class="text-right text-md-left">Sangat Setuju</p>
                        </div>
                    </div>
                </div>
                {{ Session::push() }}
            @endforeach
            @if( $questions->currentPage() == $questions->lastPage() )
                <div class="text-center pb-5">
                    {{ Form::submit('Submit!', ['class' => 'btn header-button btn-md']) }}
                </div>
            @endif
            <div class="text-center">
                {{ $questions->links() }}
            </div>
        {{ Form::close() }}

    </div>
@endsection


