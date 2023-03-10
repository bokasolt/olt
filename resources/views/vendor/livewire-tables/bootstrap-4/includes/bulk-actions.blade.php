@if (count($bulkActions))
    <div class="dropdown">
        <button class="btn dropdown-toggle d-block w-100 d-md-inline" type="button" id="bulkActions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @lang('Bulk Actions')
        </button>

        <div class="dropdown-menu dropdown-menu-right w-100" aria-labelledby="bulkActions">
            @foreach($bulkActions as $action => $title)
                <a
                    href="#"
                   wire:click.prevent="{{ $action }}"
                   wire:key="bulk-action-{{ $action }}"
                   class="dropdown-item"
                >
                    {{ $title }}
                </a>
            @endforeach
        </div>
    </div>
@endif
