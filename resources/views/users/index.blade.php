@extends('layouts.admin')

@section('content')
<user-index :role_id="{{ $role_id }}" :id="{{ $id?? 0 }}" :groups="{{ $groups ?? 0 }}"></user-index>
@endsection