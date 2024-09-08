<!-- Extendamo layout za admin portal -->
@extends('admin.layout.layout')

@section('c-icon') <i class="fas fa-mobile"></i> @endsection
@section('c-title') {{ __('Preview all splash screens') }} @endsection
@section('c-breadcrumbs')
    <a href="#"> <i class="fas fa-home"></i> <p>{{ __('Dashboard') }}</p> </a> / <a href="#">{{ __('Settings') }}</a> / <a href="{{ route('admin.config.splash') }}">{{ __('Splash screens') }}</a>
@endsection
@section('c-buttons')
    <a href="{{ route('admin.config.splash') }}">
        <button class="pm-btn btn btn-dark"> <i class="fas fa-star"></i> </button>
    </a>
    <a href="{{ route('admin.config.splash.create') }}">
        <button class="pm-btn btn pm-btn-success">
            <i class="fas fa-plus"></i>
            <span>{{ __('Add new') }}</span>
        </button>
    </a>
@endsection

@section('content')
    <div class="content-wrapper content-wrapper-p-15">
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        @include('admin.layout.snippets.filters.filter-header', ['var' => $screens])
        <table class="table table-bordered" id="filtering">
            <thead>
            <tr>
                <th scope="col" style="text-align:center;">#</th>
                @include('admin.layout.snippets.filters.filters_header')
                <th width="120p" class="akcije text-center">{{__('Actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @php $i=1; @endphp
            @foreach($screens as $screen)
                <tr>
                    <td class="text-center">{{ $i++}}</td>
                    <td> {{ $screen->title ?? ''}} </td>
                    <td> <a href="{{ $screen->fileRel->fullFrontName() }}" target="_blank">{{ __('Preview image') }}</a> </td>
                    <td> {{ $screen->views ?? ''}} </td>

                    <td class="text-center">
                        <a href="{{ route('admin.config.splash.edit', ['id' => $screen->id ]) }}" title="{{ __('More info') }}">
                            <button class="btn btn-dark btn-xs">{{ __('Edit') }}</button>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('admin.layout.snippets.filters.pagination', ['var' => $screens])
    </div>

@endsection
