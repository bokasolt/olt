<div class="card">
    @unless (isset($hideFilterLabel) && $hideFilterLabel)
        <label class="card-header">
            {!! $filter->name() !!}
        </label>
    @endunless
    <div class="card-body p-2">
        @foreach($filter->options() as $k => $value)
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" wire:model="filters.{{ $column }}.{{ $k }}"
                       id="filter.{{ $column }}.{{ $k }}"
                       value="1">
                <label for="filter.{{ $column }}.{{ $k }}" class="form-check-label"> {{$value}} </label>
            </div>
        @endforeach
    </div>
</div>