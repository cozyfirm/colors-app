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
    <a href="{{ route('admin.core.clubs.preview', ['id' => $club->id ]) }}">
        <button class="pm-btn btn pm-btn-info">
            <i class="fas fa-chevron-left "></i>
            <span>{{ __('Back') }}</span>
        </button>
    </a>
@endsection

@section('content')
    <div class="content-wrapper content-wrapper-p-15">
        <div class="row">
            <div class="col-md-9">
                <form action="{{ route('admin.core.clubs.update-venue') }}" method="POST" >
                    {{ html()->hidden('id')->class('form-control')->value($venue->id) }}
                    {{ html()->hidden('club_id')->class('form-control')->value($club->id) }}
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Venue name'))->for('name') }}</b>
                                {{ html()->text('name')->class('form-control form-control-sm mt-1')->required()->maxlength(100)->value((isset($venue) ? $venue->name : ''))->isReadonly(isset($preview)) }}
                                <small id="nameHelp" class="form-text text-muted">{{ __('Full venue name') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Address'))->for('address') }}</b>
                                {{ html()->text('address')->class('form-control form-control-sm mt-1')->required()->maxlength(100)->value((isset($venue) ? $venue->address : ''))->isReadonly(isset($preview)) }}
                                <small id="addressHelp" class="form-text text-muted">{{ __('Venue address') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__("City"))->for('city') }}</b>
                                {{ html()->text('city')->class('form-control form-control-sm mt-1')->required()->maxlength(100)->value((isset($venue) ? $venue->city : ''))->isReadonly(isset($preview)) }}
                                <small id="cityHelp" class="form-text text-muted">{{ __('Venue city') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Capacity'))->for('capacity') }}</b>
                                {{ html()->number('capacity', '', 0, 300000, 1)->class('form-control form-control-sm mt-1')->required()->maxlength(100)->value((isset($venue) ? $venue->capacity : ''))->isReadonly(isset($preview)) }}
                                <small id="capacityHelp" class="form-text text-muted">{{ __('Capacity of venue') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-dark btn-sm"> {{ __('Update profile') }} </button>
                        </div>
                    </div>
                </form>
            </div>
            @include('admin.app.system-core.clubs.snippets.right-menu')
        </div>
    </div>
@endsection
