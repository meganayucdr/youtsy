<div class="align-center py-5">
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display:none">
        <strong>Oops!</strong> Lengkapi dulu pilihan jawaban!
    </div>
    <h1 class="text-center">Mulai Tes Sekarang!</h1>
</div>

{{ Form::open(array('route' => 'holland_tests.store_user_test')) }}
{{ csrf_field() }}
@php
    $currentPage = $questions->currentPage();
    $i = 0;
@endphp
{{ Form::hidden('last_page', $questions->lastPage()) }}
@foreach( $questions as $question )
    <div class="py-4">
        <h3 class="text-center">{{ $question->question }}</h3>
        {{ Form::hidden('questions_id['. $currentPage .']['. $i .']', $question->id) }}
        @php
            $i++;
        @endphp
        <div class="row justify-content-center">
            <div class="col-3 col-md-2">
                <p class="text-right text-md-right">Sangat Tidak Setuju</p>
            </div>
            <div class="col-5 col-md-3 text-center">
                @foreach( $options as $option )
                    <label class="container-radio px-md-3">
                        {{ Form::radio('options_id['. $question->id .']', $option->id, false) }}
                        <span class="checkmark"></span>
                    </label>
                @endforeach
            </div>
            <div class="col-md-2 col-3">
                <p class="text-left text-md-left">Sangat Setuju</p>
            </div>
        </div>
    </div>
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
