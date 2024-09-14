<!-- Extendamo layout za admin portal -->
@extends('admin.layout.layout')

@section('c-icon') <i class="fas fa-users"></i> @endsection
@section('c-title') {{ $user->name }} @endsection
@section('c-breadcrumbs')
    <a href="#"> <i class="fas fa-home"></i> <p>{{ __('Dashboard') }}</p> </a> / <a href="{{ route('admin.users.index') }}">{{ __('Users') }}</a> / <a href="#">{{ $user->name }}</a> / <a href="#">{{ __('Edit') }}</a>
@endsection
@section('c-buttons')
    <a href="{{ route('admin.users.index') }}">
        <button class="pm-btn btn btn-dark"> <i class="fas fa-star"></i> </button>
    </a>

    <a href="{{ route('admin.users.index') }}">
        <button class="pm-btn btn pm-btn-info">
            <i class="fas fa-chevron-left"></i>
            <span>{{ __('Back') }}</span>
        </button>
    </a>
    <a href="{{ route('admin.users.edit', ['username' => $user->username ]) }}">
        <button class="pm-btn btn pm-btn-success">
            <i class="fas fa-edit"></i>
            <span>{{ __('Edit') }}</span>
        </button>
    </a>
@endsection

@section('content')
    <div class="content-wrapper content-wrapper-p-15">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('admin.users.update') }}" method="POST" id="JS-FORM">
                    {{ html()->hidden('id')->class('form-control')->value($user->id) }}
                    @csrf

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__("User's role"))->for('role') }}</b>
                                {{ html()->select('role', $roles)->class('form-control select-dis form-control-sm mt-1')->required()->value(isset($user) ? $user->role : '')->disabled(isset($preview)) }}
                                <small id="roleHelp" class="form-text text-muted">{{ __('Select role of user') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Active'))->for('active') }}</b>
                                {{ html()->select('active', ['0' => 'Banned', '1' => 'Active'])->class('form-control select-dis form-control-sm mt-1')->required()->value(isset($user) ? $user->active : '')->disabled(isset($preview)) }}
                                <small id="activeHelp" class="form-text text-muted">{{ __('Can user authenticate on system or not') }}</small>
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
        </div>
    </div>
@endsection
