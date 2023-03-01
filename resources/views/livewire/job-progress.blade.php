<div>
@if ($poll)
<div wire:poll>
Pending... Left: {{ $left }}
</div>
@else
	@if ($failed_count)
		<p>Unable to update ahrefs metrics for {{ $failed_count }} domains.</p>
		<p>Please see the report: <x-utils.link :href="route('admin.ahrefs.failed')" :text="__('Report')"/> </p>
	@else
		<p>Update ahrefs metrics:</p>
		<p>Done</p>
	@endif
	
@endif
</div>
