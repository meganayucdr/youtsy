@extends('layouts.app')

@section('content')
<div class="header-front-page">
    <div class="container header-caption-font py-5">
        <div class="row">
            <div class="col-md-1">
            </div>
            <div class="col-md-5 ml-5 mt-md-5 mt-2">
                <h1 class="pt-md-5 pt-0"><span class="font-weight-bolder">Kenali minat dan bakat kamu sejak sekarang</span><span>—Online, Mudah dan Gratis!</span></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1 col-1">
            </div>
            <div class="col-md-5 ml-5">
                <a href="{{ route('holland_tests.start_test') }}" class="btn btn-lg header-button">Mulai Sekarang!</a>
            </div>
        </div>
    </div>
</div>
@endsection
