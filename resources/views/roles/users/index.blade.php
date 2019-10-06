@extends('roles.show')

@include('models.children.index', [
  'resource_route' => 'roles.users',
  'model_variable' => 'user',
  'model_class' => \App\User::class,
  'parent' => $role,
  'models' => $users,
  'action_buttons_view' => 'generator::components.models.children.index.action_buttons',
])
