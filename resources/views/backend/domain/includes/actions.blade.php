<div class="text-nowrap">
@if($domain->deleted_at)
<button class="btn btn-info btn-sm mb-1" wire:click="restore({{$domain->id}})">Restore</button>
@else
<x-utils.edit-button :href="route('admin.domain.edit', $domain)" text="" />
<button class="btn btn-info btn-sm mb-1" wire:click="updateAhrefsMetrics({{$domain->id}})"><i class="fas fa-search"></i></button>
<x-utils.delete-button
            :href="route('admin.domain.destroy', $domain)"
            text="" />
@endif
</div>
