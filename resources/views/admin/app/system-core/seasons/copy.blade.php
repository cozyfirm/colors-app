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
    @isset($preview)
        <a href="{{ route('admin.core.seasons.edit', ['id' => $season->id ]) }}">
            <button class="pm-btn btn pm-btn-info">
                <i class="fas fa-edit"></i>
                <span>{{ __('Edit') }}</span>
            </button>
        </a>
        <a href="{{ route('admin.core.seasons.copy', ['id' => $season->id ]) }}">
            <button class="pm-btn btn pm-btn-success">
                <i class="fas fa-copy"></i>
                <span>{{ __('Copy season') }}</span>
            </button>
        </a>
        <a href="{{ route('admin.core.seasons.delete', ['id' => $season->id ]) }}">
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

                <form action="{{ route('admin.core.seasons.copy-season') }}" method="POST" id="js-form">
                    {{ html()->hidden('season_id')->class('form-control')->value($season->id) }}
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Season start'))->for('start_y') }}</b>
                                {{ html()->number('start_y', '', (date('Y') - 4), (date('Y') + 4), 1)->class('form-control form-control-sm mt-1')->required()->value(date('Y')) }}
                                <small id="start_yHelp" class="form-text text-muted"> {{ __('Season starting year, e.g. ') . date('Y') }} </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <b>{{ html()->label(__('Season start'))->for('end_y') }}</b>
                                {{ html()->number('end_y', '', (date('Y') - 4), (date('Y') + 4), 1)->class('form-control form-control-sm mt-1')->required()->value(date('Y') + 1) }}
                                <small id="end_yHelp" class="form-text text-muted"> {{ __('Season ending year, e.g. ') . date('Y') }} </small>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <b>{{ html()->label(__('League'))->for('league_id') }}</b>
                                {{ html()->select('league_id', $leagues)->class('form-control form-control-sm mt-1 select-2')->required()->value(isset($season) ? $season->league_id : '')->disabled(true) }}
                                <small id="league_idHelp" class="form-text text-muted">{{ __('Select league') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-dark btn-sm"> {{ __('Copy season') }} </button>
                        </div>
                    </div>
                </form>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <hr>

                        @if(isset($season) and !$season->locked)
                            <br>
                            <form action="{{ route('admin.core.seasons.save-team') }}" method="POST">
                                {{ html()->hidden('id')->class('form-control')->value($season->id) }}
                                @csrf
                                <div class="row">
                                    <div class="col-md-10">
                                        {{ html()->select('team_id', ['' => 'Select club'])->class('form-control form-control-sm mt-1 s2-search-clubs')->required() }}
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm btn-secondary w-100 h-100"> <b>{{__('Save')}}</b> </button>
                                    </div>
                                </div>
                            </form>
                        @endif

                        @if(isset($season))
                            <table class="table mt-5 table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col" class="text-center" width="60px">{{ __("#") }}</th>
                                    <th scope="col">{{ __('Club title') }}</th>
                                    <th scope="col">{{ __('Country') }}</th>
                                    @if(isset($season) and !$season->locked)
                                        <th scope="col" class="text-center" width="120px">{{ __('Actions') }}</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @php $counter = 1; @endphp
                                @foreach($season->clubsRel as $club)
                                    <tr>
                                        <th scope="row" class="text-center">{{ $counter++ }}.</th>
                                        <td> {{ $club->teamRel->name ?? '' }} </td>
                                        <td> {{ $club->teamRel->countryRel->name_ba ?? '' }} </td>
                                        @if(!$season->locked)
                                            <td class="d-flex justify-content-center">
                                                <a href="{{ route('admin.core.seasons.delete-team', ['season_id' => $season->id ?? 0, 'team_id' => $club->team_id ?? 0 ]) }}"> <button class="btn btn-dark btn-xs"> <small>{{ __('Obrišite') }}</small> </button> </a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>

            @if(isset($preview))
                @include('admin.app.system-core.seasons.includes.right-menu')
            @endif
        </div>
    </div>
@endsection
