@extends('layouts.admin')
@php $controller='\Barryvdh\TranslationManager\Controller' @endphp

@section('content')
    <div class="container-fluid mb-4">
        @include('translation-manager::_notifications')          
        @include('translation-manager::blocks._mainBlock')
        @include('translation-manager::blocks._addEditGroupKeys')
        @if($group)
            @include('translation-manager::blocks._edit')
        @else
            @if (auth()->user()->isSuperAdmin())
                @include('translation-manager::blocks._supportedLocales')
            @endif
            @include('translation-manager::blocks._publishAll')
        @endif
    </div>
@endsection

@section('scripts')
    @include('translation-manager::jsScript')
@endsection