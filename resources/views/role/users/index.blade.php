@extends('layouts.app')

@section('content-title', ucwords(__('role.users.plural')))

@include('models.index', [
  'col_class' => 'col-md-8 col-md-offset-2 offset-md-2',
  'panel_title' => ucwords(__('role.users.plural')),
  'resource_route' => 'role.users',
  'model_variable' => 'user',
  'model_class' => \App\User::class,
  'models' => $users,
  'action_buttons_view' => 'generator::components.models.index.action_buttons',
])
