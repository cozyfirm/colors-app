<div class="col-md-3">
    <div class="card">
        <div class="card-header">
            <small class="d-flex justify-content-between">
                <b> {{ __('Statistics') }} </b>
                <a href="#">
                    <i class="fas fa-chart-line pt-1"></i>
                </a>
            </small>
        </div>
        <div class="card-body">
            <ul class="list-inline">
                <li><small>{{ __('Total teams') }}: <b> {{ $season->clubsRel->count() }} </b> {{ __('u sezoni') }} {{ $season->season }}</small></li>
                <li><small>{{ __('Played games') }}: <b> {{ $season->previousMatchesCount() }} </b></small></li>
                <li><small>{{ __('Future games') }}: <b> {{ $season->nextMatchesCount() }} </b></small></li>
                <li><small>{{ __('Season start') }}: <b> {{  ($season->getFirstGame()) ? $season->getFirstGame()->date() : date('d.m.Y') }} </b></small></li>
            </ul>
        </div>
    </div>

    <hr>

    @if($season->futureMatchRel->count())
        <div class="card">
            <div class="card-header">
                <small class="d-flex justify-content-between">
                    <b> {{ __('Incoming games') }} </b>
                    <i class="fas fa-info pt-1"></i>
                </small>
            </div>
            <div class="card-body">
                <ul class="list-inline">
                    @foreach($season->futureMatchRel as $match)
                        <li title="{{ __('Game is played') }} {{ $match->date() }}">
                            {{ $match->homeRel->name ?? '' }} - {{ $match->visitorRel->name ?? '' }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if(!$season->locked)
        <div class="card mt-3">
            <a href="{{ route('admin.core.seasons.lock-season', ['id' => $season->id ]) }}" title="{{ __('Prije unosa utakmica, molimo Vas da zaključate sezonu!') }}">
                <button class="btn btn-dark btn-sm w-100"><b>{{ __('Lock season') }}</b></button>
            </a>
        </div>
    @else
        <div class="card mt-3">
            <a href="{{ route('admin.core.seasons.match-schedule', ['season_id' => $season->id ]) }}" title="{{ __('Prije unosa utakmica, molimo Vas da zaključate sezonu!') }}">
                <button class="btn btn-dark btn-sm w-100"><b>{{ __('Schedule') }}</b></button>
            </a>
        </div>
    @endif
</div>
