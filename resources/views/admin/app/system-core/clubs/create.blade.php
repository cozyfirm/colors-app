<!-- Extendamo layout za admin portal -->
@extends('admin.layout.layout')

@section('c-icon')
    @if(isset($create))
        <i class="fas fa-futbol"></i>
    @else
        <img src="{{ asset('files/core/clubs/' . $club->flag ?? '') }}" alt="">
    @endif
@endsection
@section('c-title') @isset($club) {{ $club->name }} @else {{ __('Add new team') }} @endif @endsection
@section('c-breadcrumbs')
    <a href="#"> <i class="fas fa-home"></i> <p>{{ __('Dashboard') }}</p> </a> / <a href="{{ route('admin.core.clubs') }}">{{ __('Teams') }}</a>
    @isset($preview) / <a href="#}">{{ $club->name }}</a> @endisset
@endsection
@section('c-buttons')
    <a href="{{ route('admin.core.clubs') }}">
        <button class="pm-btn btn btn-dark"> <i class="fas fa-star"></i> </button>
    </a>
    @isset($preview)
        <a href="{{ route('admin.core.clubs.edit', ['id' => $club->id ]) }}">
            <button class="pm-btn btn pm-btn-info">
                <i class="fas fa-edit"></i>
                <span>{{ __('Edit') }}</span>
            </button>
        </a>
        <a href="{{ route('admin.core.clubs.delete', ['id' => $club->id ]) }}">
            <button class="pm-btn btn pm-btn-trash">
                <i class="fas fa-trash"></i>
            </button>
        </a>
    @endisset
@endsection

@section('content')
    <div class="content-wrapper content-wrapper-p-15">
        <div class="row">
            <div class="@if(isset($preview)) col-md-9 @else col-md-12 @endif">
                @if(isset($create))
                    <form action="{{ route('admin.core.clubs.save') }}" method="POST" enctype="multipart/form-data">
                @else
                    <form action="{{ route('admin.core.clubs.update') }}" method="POST" enctype="multipart/form-data">
                    {{ html()->hidden('id')->class('form-control')->value($club->id) }}
                @endif
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <b>{{ html()->label(__('Club title'))->for('name') }}</b>
                                {{ html()->text('name')->class('form-control form-control-sm mt-1')->required()->maxlength(100)->value((isset($club) ? $club->name : ''))->isReadonly(isset($preview)) }}
                                <small id="nameHelp" class="form-text text-muted">{{ __('Full title of Club') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Short title'))->for('code') }}</b>
                                {{ html()->text('code')->class('form-control form-control-sm mt-1')->required()->maxlength(100)->value((isset($club) ? $club->code : ''))->isReadonly(isset($preview)) }}
                                <small id="codeHelp" class="form-text text-muted">{{ __('Željeni email') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Country'))->for('country_id') }}</b>
                                {{ html()->select('country_id', $countries)->class('form-control form-control-sm mt-1')->required()->value(isset($club) ? $club->country_id : '')->disabled(isset($preview)) }}
                                <small id="country_idHelp" class="form-text text-muted">{{ __('Select country') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Founded'))->for('founded') }}</b>
                                {{ html()->number('founded', '', 1900, date('Y'))->class('form-control form-control-sm mt-1')->required()->value((isset($club) ? $club->founded : ''))->isReadonly(isset($preview)) }}
                                <small id="foundedHelp" class="form-text text-muted">{{ __('Year when it is founded') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('National team'))->for('national') }}</b>
                                {{ html()->select('national', $yesNo)->class('form-control form-control-sm mt-1')->required()->value(isset($club) ? $club->national : '')->disabled(isset($preview)) }}
                                <small id="nationalHelp" class="form-text text-muted">{{ __('Is national team?') }}</small>
                            </div>
                        </div>
                    </div>

                    @if(!isset($preview))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 mt-3">
                                    <label for="flag" class="form-label"><b>{{ __('Logo kluba') }}</b></label>
                                    <input class="form-control" type="file" id="flag" name="flag" @if(isset($create)) required @endif>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-dark btn-sm"> {{ __('Update profile') }} </button>
                        </div>
                    </div>
                </form>
            </div>
            @isset($preview)
                @include('admin.app.system-core.clubs.snippets.right-menu')
            @endisset
        </div>
    </div>
@endsection
