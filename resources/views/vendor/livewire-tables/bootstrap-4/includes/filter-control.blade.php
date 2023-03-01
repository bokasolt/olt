@if ($filtersView || (count($customFilters) && $showFilters ))
    <button type="button"
            wire:click.prevent="$toggle('toggleFilters')"
            class="btn btn-outline-primary btn-light">
        @lang('Filters')

        @if (count($this->getFiltersWithoutSearch()))
            <span class="badge badge-info">
                   {{ count($this->getFiltersWithoutSearch()) }}
                </span>
        @endif
    </button>
    @if (count($this->getFiltersWithoutSearch()))
        <button type="button" wire:click.prevent="resetFilters" class="btn btn-light">Clear</button>
    @endif
@endif