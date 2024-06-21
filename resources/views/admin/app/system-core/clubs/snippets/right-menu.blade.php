<div class="col-md-3">
    <div class="card">
        <div class="card-header">
            <small class="d-flex justify-content-between">
                <b> {{ __('Stadion') }} </b>
                <a href="#">
                    <i class="fas fa-edit pt-1"></i>
                </a>
            </small>
        </div>
        <div class="card-body">
            <ul class="list-inline">
                <li><small>{{ __('Naziv') }}: <b>{{ $club->venueRel->name ?? '' }}</b></small></li>
                <li><small>{{ __('Kapacitet') }}: <b>{{ $club->venueRel->capacity ?? '' }}</b></small></li>
                <li><small>{{ __('Adresa') }}: <b>{{ $club->venueRel->address ?? '' }}</b></small></li>
                <li><small>{{ __('Grad') }}: <b>{{ $club->venueRel->city ?? '' }}</b></small></li>
            </ul>
        </div>
    </div>

    <hr>

    <div class="card">
        <div class="card-header">
            <small class="d-flex justify-content-between">
                <b> {{ __('Natjecanje u ligama') }} </b>
                <i class="fas fa-info pt-1"></i>
            </small>
        </div>
        <div class="card-body">
            <ul>
                <li>Premier liga (2023) </li>
                <li>Liga šampiona (2023) </li>
                <li>Premier liga (2022) </li>
            </ul>
        </div>
    </div>
</div>
