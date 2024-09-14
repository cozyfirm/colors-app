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
                <a href="{{ route('admin.core.league.moderators.add', ['id' => $league->id ]) }}" title="{{ __('Add new moderators') }}">
                    <i class="fas fa-plus pt-1"></i>
                </a>
            </small>
        </div>
        <div class="card-body">
            <ol class="list-inline m-0">
                @foreach($league->moderatorsRel as $moderator)
                    <li class="d-inline-flex justify-content-between w-100">
                        <a href="{{ route('admin.users.preview', ['username' => $moderator->userRel->username ?? '']) }}" target="_blank"><small> {{ $moderator->userRel->name ?? '' }} </small></a>
                        <a href="{{ route('admin.core.league.moderators.remove', ['league_id' => $league->id, 'id' => $moderator->user_id ]) }}"> <small><i class="fas fa-trash"></i></small> </a>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</div>
