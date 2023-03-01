@if ($filtersView || count($customFilters))
    <div class="collapse {{ $toggleFilters ? 'show' : '' }} clearfix ml-2 mr-2 mb-3 row justify-content-center">
        @if ($filtersView)
            @include($filtersView)
        @elseif (count($customFilters))
            @foreach ($customFilters as $column => $filter)
                <div wire:key="filter-{{ $column }}" class="p-2 col flex-grow-0" style="min-width: 210px;">
                    @include($filter->view())
                </div>
            @endforeach
        @endif
    </div>
@endif
