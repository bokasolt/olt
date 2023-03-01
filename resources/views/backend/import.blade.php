@extends('backend.layouts.app')

@section('title', __('Import'))

@section('content')
   	<x-forms.post_file :action="route('admin.import.domain.store')">
	    <x-backend.card>
	        <x-slot name="header">
    	        @lang('Import Domains')
	        </x-slot>
    
    		<x-slot name="body">
	    		<div class="form-group">
			   		<input type="file" name="file" class="form-control-file" id="customFile" assept=".xlsx,.csv">
			   	</div>
        
                <button class="btn btn-sm btn-primary" type="submit">@lang('Import')</button>
	    	</x-slot>
	    </x-backend.card>
    </x-forms.post_file>

    <x-backend.card>
        <x-slot name="header">
            @lang('Domains to Import:') {{ $toImportCount }} from {{ $total }}
        </x-slot>

         <x-slot name="headerActions">
		   	<x-forms.post :action="route('admin.import.domain.commit')">
                <button class="btn btn-sm btn-primary" type="submit">@lang('Confirm Import')</button>
            </x-forms.post>
        </x-slot>

        <x-slot name="body">
            <livewire:backend.domains-temp-import-table />
        </x-slot>
    </x-backend.card>
@endsection
