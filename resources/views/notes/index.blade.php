@extends('layouts.admin')

@section('content')

    <note-index :groups="{{ $groups?? 0 }}"></note-index>
    
@endsection