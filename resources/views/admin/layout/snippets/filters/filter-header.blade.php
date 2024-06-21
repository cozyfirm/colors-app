<div class="jumbotron d-print-none pb-3">
    <div class="row mb-2">
        <div class="col-md-12">
            <form method="GET" action="" id="filter-form">
                <div class="row">
                    <div class="col-md-4 pt-3">
                        <button type="button" class="btn btn-primary btn-xs mb-2 filter-btn new-filter">
                            <i class="fa fa-plus fa-1x"></i>
                            <a class="ml-4 text-white"><b>{{__('New filter')}}</b></a>
                        </button>

                        <div class="btn-group dropright">
                            <button type="button" class="btn btn-primary btn-xs mb-2 filter-btn show-filter-columns" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa  fa-file-excel fa-1x"></i>
                                <a class="ml-4 text-white"><b>{{__('Column name')}}</b></a>
                                <i class="fas fa-chevron-down"></i>
                            </button>

                            <div class="dropdown-menu return-none fill-column-names mt-4"
                                 style="height: 250px; overflow-y: scroll;">
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary btn-xs mb-2 filter-btn">
                            <i class="fa fa-list fa-1x"></i>
                            <a class="ml-4 text-white"><b>{{__('Update')}}</b></a>
                        </button>
                    </div>
                    <div class="col-md-7 append-filters">
                        @foreach( (request('filter') ?? [1 => 1]) as $k => $v )
                            <div class="filter-row">
                                <div class="input-group mb-2 mt-2">
                                    <div class="input-group-prepend ml-2">
                                        <select class="form-control form-control-sm filters-select"
                                                required="required"
                                                name="filter[]">
                                            <option value=""><b>{{__('Choose ...')}}</b></option>
                                            @foreach($filters as $key => $value)
                                                <option {{ ($key == $v) ? 'selected="selected"' : '' }}
                                                        value="{{ $key ?? '/'}}">
                                                    <b>{{ $value ?? '/'}}</b>
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="text" required="required" name="filter_values[]" value="{{ request('filter_values')[$k] ?? ''}}" class="form-control filters-input"/>
                                    <div class="rf-wrapper remove-filter">
                                        <i class="fa fa-times fa-1x disable-popup"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-md-1 text-center" style="padding-top:7px;">
                        <select form="filter-form" class="form-control form-control-sm col-md-2 d-inline-block filters-select" name="limit" onchange="this.form.submit()">
                            @foreach([12, 24, 48, 96] as $k)
                                <option class="text-center p-0 m-0" {{ (request()->get('limit') == $k) ? 'selected="selected"' : '' }} value="{{ $k }}">{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
