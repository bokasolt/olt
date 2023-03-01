<div class="card">
    <label class="card-header">
        {{ $filter->name() }}
    </label>
    <div class="card-body">
        <select
                onclick="event.stopPropagation();"
                wire:model="filters.{{ $column }}"
                id="filter-{{ $column }}"
                class="form-control"
        >
            @foreach($filter->options() as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>
