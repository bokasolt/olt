@if (isset($checkbox_actions) && isset($checkbox_actions['delete']) && $checkbox_actions['delete'])
@if (count($checkbox_values))
<button class="btn btn-danger btn-sm ml-2" wire:click="deleteDomains" >@lang('Delete')</button>
@else
<button class="btn btn-danger btn-sm ml-2" wire:click="deleteDomains" disabled>@lang('Delete')</button>
@endif
@endif

