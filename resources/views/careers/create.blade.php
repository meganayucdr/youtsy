@extends('layouts.app')

@section('content-title', ucwords(__('careers.plural')))

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 offset-md-2">
            <form action="/careers" method="POST" class="form">
                {{ csrf_field() }}
                <input type="hidden" name="_token">
                <div class="panel panel-default card"><div class="panel-heading card-header">
                        <span class="panel-title">Create Role</span>
                    </div>
                    <div class="panel-body card-body">
                        <div class="form-group">
                            <label for="career" class="control-label">Career</label>
                            <input id="name" type="text" title="Career Name" name="name" value="" required="required" class="form-control ">
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
            </form>
        </div>
    </div>
@endsection
