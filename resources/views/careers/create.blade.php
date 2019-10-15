@extends('layouts.app')

@section('content-title', ucwords(__('careers.plural')))

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 offset-md-2">
            {!! Form::open(['route' => 'careers.store']) !!}
                {{ csrf_field() }}
                <input type="hidden" name="_token">
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
                            <button type="submit" name="redirect" value="https://youtsy.wew/roles?" class="btn btn-primary">
                                Store
                            </button>

                            <button type="submit" name="redirect" value="https://youtsy.wew/roles/create?redirect=https%3A%2F%2Fyoutsy.wew%2Froles%3F" class="btn btn-primary">
                                Store &amp; Create
                            </button>
                        </div>
                        <a href="https://youtsy.wew/roles?" class="btn btn-default btn-secondary">Back</a>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
