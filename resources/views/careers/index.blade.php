@extends('layouts.app')

@section('content-title', ucwords(__('careers.plural')))

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 offset-md-2">
            <div class="panel panel-default card"><div class="panel-heading card-header">
                    <span class="panel-title">List Careers</span>
                    <div class="pull-right float-right">
                        <a href="{{ url('careers/create') }}" class="btn btn-default btn-secondary btn-sm btn-xs">Create</a>
                    </div>
                </div>
                <div class="panel-body card-body">
                    <form id="search" method="GET" class="form">
                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-xs-6 col-6 col-md-4">
                                <div class="input-group">
                                    <span class="input-group-btn input-group-prepend">
                                        <button type="submit" class="btn btn-default btn-secondary btn-sm"></button>
                                    </span>
                                    <input type="text" name="search" placeholder="Search" value="" autofocus="autofocus" class="input-sm form-control form-control-sm">
                                </div>
                            </div> <div class="col-xs-6 col-6 col-md-8">
                                <div class="text-right">
                                    <span></span>
                                    <div style="display: inline-block;">
                                        <select name="per_page" id="per_page" onchange="this.form.submit()" title="per page" class="form-control form-control-sm input-sm">
                                            <option value="15" selected="selected">15</option>
                                            <option value="50">50</option> <option value="100">100</option>
                                            <option value="250">250</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table id="careers" class="table table-striped table-hover table-condensed table-sm">
                            <thead class="text-nowrap">
                            <tr>
                                <th width="1px" class="text-center">No</th>
                                <th class="text-center">
                                    Career
                                    <a href="https://youtsy.wew/careers?sort=career%2Cdesc">
                                        <i class="fa fa-sort text-muted"></i>
                                    </a>
                                </th>
                                <th width="1px" class="text-center action">

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>

                                </td>
                                <td>
                                    <input name="career" value="" placeholder="All" form="search" class="form-control">
                                </td>
                                <td>

                                </td>
                            </tr>
                            @foreach($careers as $career)
                            <tr>
                                <td class="text-right"></td>
                                <td>
                                    {{ $career->name }}
                                </td>
                                <td class="action text-nowrap text-right">
                                    <a href="{{ route('careers.show', $career->id) }}" class="btn btn-primary btn-sm btn-xs">Show</a>
                                    <a href="{{ route('careers.edit', $career->id) }}" class="btn btn-success btn-sm btn-xs">Edit</a>
                                    <form action="{{ route('careers.destroy', $career->id) }}"
                                          method="POST" onsubmit="return confirm('Are you sure you want to Delete?');"
                                          style="display: inline;"><input type="hidden" name="_token">
                                        <button type="submit" name="_method" value="DELETE" class="btn btn-danger btn-sm btn-xs">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
