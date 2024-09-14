<div class="col-md-3">
    <div class="card">
        <div class="card-header">
            <small class="d-flex justify-content-between">
                <b> {{ __('Previous seasons') }} </b>
                <a href="#">
                    <i class="fas fa-chart-line pt-1"></i>
                </a>
            </small>
        </div>
        <div class="card-body">
            <ul class="list-inline m-0">
                @foreach($league->seasonsRel as $season)
                    <li><a href="{{ route('admin.core.seasons.preview', ['id' => $season->id]) }}" target="_blank"><small> {{ $season->season }} </small></a></li>
                @endforeach
            </ul>
        </div>
    </div>

    <hr>

    <div class="card">
        <div class="card-header">
            <small class="d-flex justify-content-between">
                <b> {{ __('Moderators') }} </b>
                <a href="#" title="{{ __('Add new moderators') }}">
                    <i class="fas fa-plus pt-1"></i>
                </a>
            </small>
        </div>
        <div class="card-body">
            <ul class="list-inline m-0">
                @foreach($league->seasonsRel as $season)
                    <li><a href="{{ route('admin.core.seasons.preview', ['id' => $season->id]) }}" target="_blank"><small> {{ $season->season }} </small></a></li>
                @endforeach
            </ul>
        </div>
    </div>
{{--    @if($season->futureMatchRel->count())--}}
{{--        <div class="card">--}}
{{--            <div class="card-header">--}}
{{--                <small class="d-flex justify-content-between">--}}
{{--                    <b> {{ __('Incoming games') }} </b>--}}
{{--                    <i class="fas fa-info pt-1"></i>--}}
{{--                </small>--}}
{{--            </div>--}}
{{--            <div class="card-body">--}}
{{--                <ul class="list-inline">--}}
{{--                    @foreach($season->futureMatchRel as $match)--}}
{{--                        <li title="{{ __('Game is played') }} {{ $match->date() }}">--}}
{{--                            {{ $match->homeRel->name ?? '' }} - {{ $match->visitorRel->name ?? '' }}--}}
{{--                        </li>--}}
{{--                    @endforeach--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}

    <div class="card mt-3">
        <a href="#" title="{{ __('Prije unosa utakmica, molimo Vas da zaključate sezonu!') }}">
            <button class="btn btn-dark btn-sm w-100"><b>{{ __('Schedule') }}</b></button>
        </a>
    </div>
</div>
