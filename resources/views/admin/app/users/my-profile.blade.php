<!-- Extendamo layout za admin portal -->
@extends('admin.layout.layout')

@section('c-icon') <i class="fas fa-users"></i> @endsection
@section('c-title') {{ Auth()->user()->name }} @endsection
@section('c-breadcrumbs')
    <a href="#"> <i class="fas fa-home"></i> <p>{{ __('Dashboard') }}</p> </a> / <a href="{{ route('admin.users.index') }}">{{ __('Users') }}</a> / <a href="#">{{ Auth()->user()->name }}</a>
@endsection
@section('c-buttons')
    <a href="{{ route('admin.users.index') }}">
        <button class="pm-btn btn btn-dark"> <i class="fas fa-star"></i> </button>
    </a>
@endsection

@section('content')
    <div class="content-wrapper content-wrapper-p-15">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <b>{{ html()->label(__('Full name'))->for('name') }}</b>
                            {{ html()->text('name')->class('form-control form-control-sm mt-1')->required()->maxlength(100)->value((isset($user) ? $user->name : ''))->isReadonly(isset($preview)) }}
                            <small id="nameHelp" class="form-text text-muted">{{ __('Name and surname of user') }}</small>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <b>{{ html()->label(__('Email'))->for('email') }}</b>
                            {{ html()->text('email')->class('form-control form-control-sm mt-1')->required()->maxlength(100)->value((isset($user) ? $user->email : ''))->isReadonly(isset($preview)) }}
                            <small id="emailHelp" class="form-text text-muted">{{ __('Email address') }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <b>{{ html()->label(__('Username'))->for('username') }}</b>
                            {{ html()->text('username')->class('form-control form-control-sm mt-1')->required()->maxlength(100)->value((isset($user) ? $user->username : ''))->isReadonly(isset($preview)) }}
                            <small id="passwordHelp" class="form-text text-muted">{{ __('Selected username') }}</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <b>{{ html()->label(__('Birth date'))->for('birth_date') }}</b>
                            {{ html()->text('birth_date')->class('form-control form-control-sm mt-1')->required()->maxlength(100)->value((isset($user) ? $user->birthDate() : ''))->isReadonly(isset($preview)) }}
                            <small id="birth_dateHelp" class="form-text text-muted">{{ __("User's birth date") }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <b>{{ html()->label(__('Prefix'))->for('prefix') }}</b>
                                    {{ html()->select('prefix', $prefixes)->class('form-control select-dis form-control-sm mt-1')->required()->value(isset($user) ? $user->prefix : '')->disabled(isset($preview)) }}
                                    <small id="prefixHelp" class="form-text text-muted">{{ __('Full phone number') }}</small>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <b>{{ html()->label(__('Phone number'))->for('phone') }}</b>
                                    {{ html()->text('phone')->class('form-control form-control-sm mt-1')->required()->maxlength(100)->value((isset($user) ? $user->phone : ''))->isReadonly(isset($preview)) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <b>{{ html()->label(__('Country'))->for('country') }}</b>
                            {{ html()->select('country', $countries)->class('form-control select-dis form-control-sm mt-1')->required()->value(isset($user) ? $user->country : '')->disabled(isset($preview)) }}
                            <small id="countryHelp" class="form-text text-muted">{{ __('Users country name') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
