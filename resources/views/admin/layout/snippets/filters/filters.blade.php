<div class="jumbotron d-print-none" style="padding: 1em 1em; background: rgba(0,0,0,0.05)">
    <div class="row mt-2 mb-2">
        <div class="col-md-6">
            <form method="GET" action="" id="filter-form">
                <div class="row">
                    <div class="col-md-8 append-filters">
                        <button style="padding: 3px 10px !important;" type="button" class="btn btn-success btn-xs new-filter mb-2">
                            <i class="fa fa-plus fa-1x"></i> {{__('Novi filter')}}
                        </button>

                        <div class="btn-group dropright" style="border: 0px;">
                            <button style="padding: 3px 10px !important;" type="button"
                                    class="btn btn-secondary btn-xs mb-2 dropdown-toggle show-filter-columns" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-columns"></i> {{__('Kolone')}}
                            </button>
                            <div class="dropdown-menu return-none fill-column-names"
                                 style="height: 250px; overflow-y: scroll;">
                            </div>
                        </div>

                        <button style="padding: 3px 10px !important;" type="submit" class="btn btn-primary btn-xs mb-2">
                            <i class="fa fa-list fa-1x"></i>
                            {{__('Pregled')}}
                        </button>

                        <div class="btn-group dropright" style="border: 0px;">
                            <button style="padding: 3px 10px !important;" type="button"
                                    class="btn btn-secondary btn-xs mb-2 dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"
                                    onclick="getVisibleColumns('excel')"
                            >
                                <i class="fa  fa-file-excel fa-1x"></i> {{__('Excel')}}
                            </button>
                        </div>

                        @foreach( (request('filter') ?? [1 => 1]) as $k => $v )

                            <div class="filter-row">
                                <div class="input-group mb-2 mt-2">
                                    <div class="remove-filter-w" style="width: 30px; background: #ffffff; height: 30px; margin-right: 5px; margin-top: 0px; text-align: center; border: 1px solid rgba(0,0,0,0.2); border-radius:3px; padding-top: 2px;">
                                        <i class="fa fa-times fa-1x mt-1 mr-3 disable-popup remove-filter" style="color: red; cursor: pointer;"></i>
                                    </div>
                                    <div class="input-group-prepend">
                                        <select class="form-control form-control-sm" required="required"  name="filter[]">
                                            <option value="">{{__('Odaberi...')}}</option>
                                            @foreach($filters as $key => $value)
                                                <option {{ ($key == $v) ? 'selected="selected"' : '' }}
                                                        value="{{ $key ?? '/'}}">
                                                    {{ $value ?? '/'}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="text" required="required" name="filter_values[]"  value="{{ request('filter_values')[$k] ?? ''}}"  style="height: 31px;"  class="form-control"/>
                                </div>
                            </div>

                        @endforeach
                    </div>

                </div>
            </form>
        </div>
        <div class="col-md-6 text-right">
            <div class="col-md-12 mb-3 d-inline-flex">
                <div class="w-25 pt-1">
                    {{__('Ukupno:')}}
                    <b>{{ $var->total() }}</b>
                    {{__('prikaži:')}}
                </div>

                <select form="filter-form"
                        class="form-control form-control-sm"
                        name="limit"
                        onchange="this.form.submit()">
                    @foreach([15, 25, 50, 100, 200, 500] as $k)
                        <option {{ (request()->get('limit') == $k) ? 'selected="selected"' : '' }} value="{{ $k }}">{{ $k }}</option>
                    @endforeach
                </select>

            </div>
            <div class="col-md-12 text-right pull-pagination-right">
                {{ $var->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
