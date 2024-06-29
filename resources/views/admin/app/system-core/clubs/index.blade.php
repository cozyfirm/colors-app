<!-- Extendamo layout za admin portal -->
@extends('admin.layout.layout')

@section('c-icon') <i class="fas fa-futbol"></i> @endsection
@section('c-title') {{ __('Preview all teams') }} @endsection
@section('c-breadcrumbs')
    <a href="#"> <i class="fas fa-home"></i> <p>{{ __('Dashboard') }}</p> </a> / <a href="{{ route('admin.core.clubs') }}">{{ __('Teams') }}</a>
@endsection
@section('c-buttons')
    <a href="{{ route('admin.core.clubs') }}">
        <button class="pm-btn btn btn-dark"> <i class="fas fa-star"></i> </button>
    </a>
    <a href="{{ route('admin.core.clubs.create') }}">
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

        @include('admin.layout.snippets.filters.filter-header', ['var' => $clubs])
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
            @foreach($clubs as $club)
                <tr>
                    <td class="text-center">{{ $i++}}</td>
                    <td class="inline-img">
                        <img src="{{ asset('files/core/clubs/' . $club->flag ?? '') }}" alt="">
                        {{ $club->name ?? ''}}
                    </td>
                    <td> {{ $club->code ?? ''}} </td>
                    <td> {{ $club->countryRel->name_ba ?? ''}} </td>
                    <td> {{ $club->founded ?? ''}} </td>
                    <td> {{ $club->nationalRel->name ?? ''}} </td>

                    <td class="text-center">
                        <a href="{{ route('admin.core.clubs.preview', ['id' => $club->id ]) }}" title="{{ __('More info') }}">
                            <button class="btn btn-dark btn-xs">{{ __('Preview') }}</button>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('admin.layout.snippets.filters.pagination', ['var' => $clubs])
    </div>

@endsection
