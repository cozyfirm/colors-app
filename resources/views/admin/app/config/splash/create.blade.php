<!-- Extendamo layout za admin portal -->
@extends('admin.layout.layout')

@section('c-icon') <i class="fas fa-mobile"></i> @endsection
@section('c-title') @isset($screen) {{ $screen->title }} @else {{ __('Add splash screen') }} @endif @endsection
@section('c-breadcrumbs')
    <a href="#"> <i class="fas fa-home"></i> <p>{{ __('Dashboard') }}</p> </a> / <a href="#">{{ __('Settings') }}</a> / <a href="{{ route('admin.config.splash') }}">{{ __('Splash screens') }}</a>
    @if(isset($screen)) / <a href="#">{{ $screen->title }}</a> @else / <a href="#">{{ __('Add new splash screen') }}</a> @endif
@endsection
@section('c-buttons')
    <a href="{{ route('admin.config.splash') }}">
        <button class="pm-btn btn btn-dark"> <i class="fas fa-star"></i> </button>
    </a>
    @isset($edit)
        <a href="{{ route('admin.config.splash.delete', ['id' => $screen->id ]) }}">
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
                    <form action="{{ route('admin.config.splash.save') }}" method="POST" enctype="multipart/form-data">
                @else
                    <form action="{{ route('admin.config.splash.update') }}" method="POST" enctype="multipart/form-data">
                    {{ html()->hidden('id')->class('form-control')->value($screen->id ?? '') }}
                @endif
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <b>{{ html()->label(__('Screen title'))->for('title') }}</b>
                                {{ html()->text('title')->class('form-control form-control-sm mt-1')->required()->maxlength(100)->value((isset($screen) ? $screen->title : ''))->isReadonly(isset($preview)) }}
                                <small id="titleHelp" class="form-text text-muted">{{ __('Splash screen title') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 mt-3">
                                <label for="image" class="form-label"><b>{{ __('Image') }}</b></label>
                                <input class="form-control" type="file" id="image" name="image" @if(isset($create)) required @endif>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-dark btn-sm"> {{ __('Update data') }} </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
