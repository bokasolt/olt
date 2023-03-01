@if (isset($exports) &&  count($exports))
    <div class="dropdown">
        <button class="btn dropdown-toggle d-block w-100 d-md-inline" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @lang('Export')
        </button>

        <div class="dropdown-menu dropdown-menu-right w-100" aria-labelledby="dropdownMenuButton">
            @if (in_array('csv', $exports, true))
                <a class="dropdown-item" href="#" wire:click.prevent="export('csv')">CSV</a>
            @endif

            @if (in_array('xls', $exports, true))
                <a class="dropdown-item" href="#" wire:click.prevent="export('xls')">XLS</a>
            @endif

            @if (in_array('xlsx', $exports, true))
                <a class="dropdown-item" href="#" wire:click.prevent="export('xlsx')">XLSX</a>
            @endif

            @if (in_array('pdf', $exports, true))
                <a class="dropdown-item" href="#" wire:click.prevent="export('pdf')">PDF</a>
            @endif
        </div>
    </div><!--export-dropdown-->
@endif
