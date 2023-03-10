<div
    @if (is_numeric($refresh))
        wire:poll.{{ $refresh }}ms
    @elseif(is_string($refresh))
        @if ($refresh === '.keep-alive' || $refresh === 'keep-alive')
            wire:poll.keep-alive
        @elseif($refresh === '.visible' || $refresh === 'visible')
            wire:poll.visible
        @else
            wire:poll="{{ $refresh }}"
        @endif
    @endif
    class="container-fluid p-0"
>
    @include('livewire-tables::bootstrap-4.includes.offline')
    @include('livewire-tables::bootstrap-4.includes.sorting-pills')
    @include('livewire-tables::bootstrap-4.includes.filter-pills')

    <div class="d-flex justify-content-between mb-3">
        <div class="d-flex flex-grow-1">
            @include('livewire-tables::bootstrap-4.includes.search')

            <div class="ml-0 ml-md-3 mb-3 mb-md-0 text-nowrap">
                @include('livewire-tables::bootstrap-4.includes.filter-control')
            </div>
        </div>

        <div class="d-flex">
            @include('livewire-tables::bootstrap-4.includes.bulk-actions')
            @include('livewire-tables::bootstrap-4.includes.export')
            @if (isset($actions))
                @include($actions)
            @endif
            @include('livewire-tables::bootstrap-4.includes.per-page')
        </div>
    </div>
    @include('livewire-tables::bootstrap-4.includes.filters')

    @include('livewire-tables::bootstrap-4.includes.table')
    @include('livewire-tables::bootstrap-4.includes.pagination')
</div>
