<!-- Extendamo layout za admin portal -->
@extends('admin.layout.layout')

@section('c-icon') <img src="{{ asset('files/core/leagues/' . $league->logo ?? '') }}" alt=""> @endsection
@section('c-title') {{ $league->name }} @endsection
@section('c-breadcrumbs')
    <a href="#"> <i class="fas fa-home"></i> <p>{{ __('Dashboard') }}</p> </a> / <a href="{{ route('admin.core.league') }}">{{ __('Leagues') }}</a> / <a href="{{ route('admin.core.league.preview', ['id' => $league->id ]) }}">{{ $league->name }}</a> / <a href="#">{{ __('Add moderators') }}</a>
@endsection
@section('c-buttons')
    <a href="{{ route('admin.core.league') }}">
        <button class="pm-btn btn btn-dark"> <i class="fas fa-star"></i> </button>
    </a>
    <a href="{{ route('admin.core.league.preview', ['id' => $league->id ]) }}">
        <button class="pm-btn btn pm-btn-info">
            <i class="fas fa-chevron-left"></i>
            <span>{{ __('Back') }}</span>
        </button>
    </a>
@endsection

@section('content')
    <div class="content-wrapper content-wrapper-p-15">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('admin.core.league.moderators.save') }}" method="POST" id="js-form">
                    {{ html()->hidden('league_id')->class('form-control')->value($league->id) }}

                    <div class="row">
                        <div class="col-md-9">
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <b>{{ html()->label(__('User'))->for('user_id') }}</b>
                                        {{ html()->select('user_id', $users )->class('form-control select-2 form-control-sm mt-1')->required()->value() }}
                                        <small id="user_idHelp" class="form-text text-muted">{{ __('Select moderator for this league') }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-dark btn-sm"> {{ __('Add moderator') }} </button>
                                </div>
                            </div>
                        </div>

                        @include('admin.app.system-core.league.includes.right-menu')
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
