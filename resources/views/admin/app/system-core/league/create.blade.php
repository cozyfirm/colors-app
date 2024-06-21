<!-- Extendamo layout za admin portal -->
@extends('admin.layout.layout')

@section('c-icon')
    @if(isset($create))
        <i class="fas fa-feather"></i>
    @else
        <img src="{{ asset('files/core/leagues/' . $league->logo ?? '') }}" alt="">
    @endif
@endsection
@section('c-title') @isset($league) {{ $league->name }} @else {{ __('Add new league') }} @endif @endsection
@section('c-breadcrumbs')
    <a href="#"> <i class="fas fa-home"></i> <p>{{ __('Dashboard') }}</p> </a> / <a href="{{ route('admin.core.league') }}">{{ __('Leagues') }}</a> / <a href="#}">{{ $league->name }}</a>
@endsection
@section('c-buttons')
    <a href="{{ route('admin.core.league') }}">
        <button class="pm-btn btn btn-dark"> <i class="fas fa-star"></i> </button>
    </a>
    @isset($preview)
        <a href="{{ route('admin.core.league.edit', ['id' => $league->id ]) }}">
            <button class="pm-btn btn pm-btn-info">
                <i class="fas fa-edit"></i>
                <span>{{ __('Edit') }}</span>
            </button>
        </a>
        <a href="{{ route('admin.core.league.delete', ['id' => $league->id ]) }}">
            <button class="pm-btn btn pm-btn-trash">
                <i class="fas fa-trash"></i>
            </button>
        </a>
    @endisset
@endsection

@section('content')
    <div class="content-wrapper content-wrapper-p-15">
        <div class="row">
            <div class="col-md-12">
                @if(isset($create))
                    <form action="{{ route('admin.core.league.save') }}" method="POST" enctype="multipart/form-data">
                @else
                <form action="{{ route('admin.core.league.update') }}" method="POST" enctype="multipart/form-data">
                    {{ html()->hidden('id')->class('form-control')->value($league->id) }}
                @endif
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <b>{{ html()->label(__('Title'))->for('name') }}</b>
                                {{ html()->text('name')->class('form-control form-control-sm mt-1')->required()->maxlength(100)->value((isset($league) ? $league->name : ''))->isReadonly(isset($preview)) }}
                                <small id="nameHelp" class="form-text text-muted">{{ __('Full title of League') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Type'))->for('type') }}</b>
                                {{ html()->select('type', $type)->class('form-control form-control-sm mt-1')->required()->value(isset($league) ? $league->type : '')->disabled(isset($preview)) }}
                                <small id="typeHelp" class="form-text text-muted">{{ __('Select league type') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Country'))->for('country_id') }}</b>
                                {{ html()->select('country_id', $countries)->class('form-control form-control-sm mt-1')->required()->value(isset($league) ? $league->country_id : '')->disabled(isset($preview)) }}
                                <small id="country_idHelp" class="form-text text-muted">{{ __('Select country') }}</small>
                            </div>
                        </div>
                    </div>

                    @if(!isset($preview))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 mt-3">
                                    <label for="flag" class="form-label"><b>{{ __('Logo kluba') }}</b></label>
                                    <input class="form-control" type="file" id="logo" name="logo" @if(isset($create)) required @endif>
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

        </div>
    </div>
@endsection
