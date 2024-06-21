<!-- Extendamo layout za admin portal -->
@extends('admin.layout.layout')

@section('c-icon') <i class="fas fa-feather"></i> @endsection
@section('c-title') {{ __('Preview all leagues') }} @endsection
@section('c-breadcrumbs')
    <a href="#"> <i class="fas fa-home"></i> <p>{{ __('Dashboard') }}</p> </a> / <a href="{{ route('admin.core.league') }}">{{ __('Leagues') }}</a>
@endsection
@section('c-buttons')
    <a href="{{ route('admin.core.league') }}">
        <button class="pm-btn btn btn-dark"> <i class="fas fa-star"></i> </button>
    </a>
    <a href="{{ route('admin.core.league.create') }}">
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

        @include('admin.layout.snippets.filters.filter-header', ['var' => $leagues])
        <table class="table table-bordered" id="filtering">
            <thead>
            <tr>
                <th scope="col" style="text-align:center;">#</th>
                @include('admin.layout.snippets.filters.filters_header')
                <th width="120p" class="akcije text-center">{{__('Akcije')}}</th>
            </tr>
            </thead>
            <tbody>
            @php $i=1; @endphp
            @foreach($leagues as $league)
                <tr>
                    <td class="text-center">{{ $i++}}</td>
                    <td> {{ $league->name ?? ''}} </td>
                    <td> {{ $league->typeRel->name ?? ''}} </td>
                    <td> {{ $league->countryRel->name_ba ?? ''}} </td>

                    <td class="text-center">
                        <a href="{{ route('admin.core.league.preview', ['id' => $league->id ]) }}" title="{{ __('More info') }}">
                            <button class="btn btn-dark btn-xs">{{ __('Preview') }}</button>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('admin.layout.snippets.filters.pagination', ['var' => $leagues])
    </div>

@endsection
