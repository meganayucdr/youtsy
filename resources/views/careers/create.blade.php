@extends('layouts.app')

@section('content-title', ucwords(__('careers.plural')))

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 offset-md-2">
            {!! Form::open(['url' => 'careers', 'method' => 'post']) !!}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="panel panel-default card"><div class="panel-heading card-header">
                        <span class="panel-title">Create Role</span>
                    </div>
                    <div class="panel-body card-body">
                        <div class="form-group">
                            {!! Form::label('career', 'Career Name') !!}
                            {!! Form::text('career_name', '', ['class' => 'form-control mb-2', 'required' => 'required']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('holland_code', 'Holland Code') !!}
                            {!! Form::select('holland_codes[]',
                                $holland_codes,
                                null,
                                ['class' => 'form-control',
                                'multiple' => 'multiple']) !!}
                        </div>
                    </div>
                    <div class="panel-footer card-footer clearfix">
                        <div class="pull-right float-right">
                            {!! Form::submit('Store', ['class' => 'btn btn-primary']) !!}
                            <button type="submit" name="redirect" value="https://youtsy.wew/careers/create" class="btn btn-primary">
                                Store &amp; Create
                            </button>
                        </div>
                        <a href="https://youtsy.wew/careers?" class="btn btn-default btn-secondary">Back</a>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
