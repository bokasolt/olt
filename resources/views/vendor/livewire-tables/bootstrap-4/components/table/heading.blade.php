@props([
'column',
'sortable' => null,
'direction' => null,
'text' => null,
'short' => null,
'filter' => null,
'hideFilterLabel' => true,
'showFilters' => false
])
@if ($text == 'checkboxall')
    <th {{ $attributes->only('class') }}><input type="checkbox" wire:model="checkbox_all"></th>
@else
    @unless ($sortable)
        <th {{ $attributes->only('class') }}>
            {{ $text ?? $slot }}
        </th>
    @else
        <th
                {{ $attributes->only('class') }}
                style="cursor: pointer;"
                data-toggle="tooltip" data-placement="top" title="{{ $text ?? $column }}"
        >
            <div class="d-flex align-items-center position-relative">
                <span wire:click="sortBy('{{ $column }}', '{{ $text ?? $column }}')">{{ $short ?? $text }}</span>

                <span wire:click="sortBy('{{ $column }}', '{{ $text ?? $column }}')"
                      class="relative d-flex align-items-center">
                @if ($direction === 'asc')
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1" style="width:1em;height:1em;" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                    </svg>
                    @elseif ($direction === 'desc')
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1" style="width:1em;height:1em;" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1" style="width:1em;height:1em;" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                    @endif
            </span>
                @if ($filter && $showFilters)
                    <button class="btn " data-toggle="dropdown"><i class="fas fa-filter"></i></button>
                    <div class="dropdown-menu p-0" wire:key="inline-filter-{{ $column }}">
                        @include($filter->view())
                    </div>
                @endif
            </div>
        </th>
    @endif
@endif