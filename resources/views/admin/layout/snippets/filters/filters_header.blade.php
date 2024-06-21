@foreach($filters as $key => $value)
    <th data-filter-id="{{ $key ?? '/'}}">{{ $value ?? '/'}}</th>
@endforeach
