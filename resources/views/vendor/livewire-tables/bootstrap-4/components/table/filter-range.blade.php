<div class="card">
    @unless (isset($hideFilterLabel) && $hideFilterLabel)
        <label class="card-header">
            {!! $filter->name() !!}
        </label>
    @endunless
    <div class="card-body p-2" style="min-width: 182px;">
        <div class="form-row">
            <div class="form-group col-6">
                <label>Min</label>
                <input type="number" class="form-control"
                       min="{{ $filter->getMin() }}" max="{{ $filter->getMax() }}"
                       placeholder="{{ $filter->getMin() }}"
                       wire:model="filters.{{ $column }}.min">
            </div>
            <div class="form-group col-6">
                <label>Max</label>
                <input type="number" class="form-control"
                       min="{{ $filter->getMin() }}" max="{{ $filter->getMax() }}"
                       placeholder="{{ $filter->getMax() }}"
                       wire:model="filters.{{ $column }}.max">
            </div>
        </div>
    </div>
</div>