@extends('frontend.layouts.app')

@section('title', __('Verify Your E-mail Address'))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <x-frontend.card>
                    <x-slot name="header">
			        	<div class="branding text-center">
				        	<img class="logo" style="height: 50px;" src="{{ asset('img/2021-04-Outreach-234x50px-SVG.svg') }}" />
			        	</div>
                        <h1 class="text-center m-4">@lang('Verify Your E-mail Address')</h1>
                    </x-slot>

                    <x-slot name="body">
                        @lang('Before proceeding, please check your email for a verification link.')
                        @lang('If you did not receive the email')

                        <x-forms.post :action="route('frontend.auth.verification.resend')" class="d-inline">
                            <button class="btn btn-link p-0 m-0 align-baseline" type="submit">@lang('click here to request another').</button>
                        </x-forms.post>
                    </x-slot>
                </x-frontend.card>
            </div><!--col-md-8-->
        </div><!--row-->
    </div><!--container-->
@endsection
