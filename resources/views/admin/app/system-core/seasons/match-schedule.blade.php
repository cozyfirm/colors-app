<!-- Extendamo layout za admin portal -->
@extends('admin.layout.layout')

@section('c-icon') <i class="fas fa-wind"></i> @endsection
@section('c-title') @isset($season) {{ $season->season }} @else {{ __('Add new season') }} @endif @endsection
@section('c-breadcrumbs')
    <a href="#"> <i class="fas fa-home"></i> <p>{{ __('Dashboard') }}</p> </a> / <a href="{{ route('admin.core.seasons') }}">{{ __('Seasons') }}</a>
    @isset($create)
        / <a href="#}">{{ __('Add new season') }}</a>
    @else
        / <a href="#}">{{ $season->season }}</a>
    @endisset
@endsection
@section('c-buttons')
    <a href="{{ route('admin.core.seasons') }}">
        <button class="pm-btn btn btn-dark"> <i class="fas fa-star"></i> </button>
    </a>
    <a href="{{ route('admin.core.seasons.preview', ['id' => $season->id ]) }}">
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
                @if(session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @elseif(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.core.seasons.system.core.seasons.save-match-schedule') }}" method="POST">
                    {{ html()->hidden('season_id')->class('form-control')->value($season->id) }}
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Home team'))->for('home_team') }}</b>
                                {{ html()->select('home_team', $teams)->class('form-control form-control-sm mt-1 select-2')->required() }}
                                <small id="home_teamHelp" class="form-text text-muted">{{ __('Select home team') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Visiting team'))->for('visiting_team') }}</b>
                                {{ html()->select('visiting_team', $teams)->class('form-control form-control-sm mt-1 select-2')->required() }}
                                <small id="visiting_teamHelp" class="form-text text-muted">{{ __('Select visiting team') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Match date'))->for('date') }}</b>
                                {{ html()->text('date')->class('form-control form-control-sm mt-1 datepicker')->required()->value(date('d.m.Y')) }}
                                <small id="dateHelp" class="form-text text-muted">{{ __('Pick a match date') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('League round'))->for('options') }}</b>
                                {{ html()->select('options', $options)->class('form-control form-control-sm mt-1 select-2')->required() }}
                                <small id="optionsHelp" class="form-text text-muted">{{ __('Select option') }}</small>
                            </div>
                        </div>
                    </div>

                    @if(!isset($preview))
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-dark btn-sm"> {{ __('Save match') }} </button>
                            </div>
                        </div>
                    @endif
                </form>

                <div class="row">
                    <div class="col-md-12">
                        <hr>

                        <table class="table mt-5 table-bordered">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center" width="40px">{{ __("#") }}</th>
                                <th scope="col">{{ __('Datum') }}</th>
                                <th scope="col">{{ __('Domaći tim') }}</th>
                                <th scope="col">{{ __('Gostujući tim') }}</th>
                                <th scope="col" class="text-center">{{ __('Kolo') }}</th>
                                <th scope="col" class="text-center" width="120px">{{ __('Akcije') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $counter = 1; @endphp
                            @foreach($season->matchRel as $match)
                                <tr>
                                    <th scope="row" class="text-center">{{ $counter++ }}.</th>
                                    <td> {{ $match->date() }} </td>
                                    <td> {{ $match->homeRel->name ?? '' }} </td>
                                    <td> {{ $match->visitorRel->name ?? '' }} </td>
                                    <td class="text-center"> {{ $match->optionsRel->name }} </td>
                                    <td class="d-flex justify-content-center">
                                        <a href="{{ route('admin.core.seasons.delete-match-schedule', ['id' => $match->id ]) }}"> <button class="btn btn-dark btn-xs"> <small>{{ __('Obrišite') }}</small> </button> </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @include('admin.app.system-core.seasons.includes.right-menu')
        </div>
    </div>
@endsection
