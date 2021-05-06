@extends('layouts.admin')

@section('content')

    <page-index :groups="{{ $groups?? 0 }}" :locales="{{ json_encode($locales) }}"></page-index>
    
@endsection