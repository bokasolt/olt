<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ appName() }}</title>
        <meta name="description" content="@yield('meta_description', appName())">
        <meta name="author" content="@yield('meta_author', 'Artem Myrhorodsky')">
        @yield('meta')

        @stack('before-styles')
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="{{ mix('css/frontend.css') }}" rel="stylesheet">
        @stack('after-styles')
    </head>
    <body>
        
        <div id="app">
            <div class="content row  m-0" style="position: fixed; top: 0; bottom: 0; left: 0; right: 0;">
				<div class="container my-auto">
        @include('includes.partials.read-only')
        @include('includes.partials.logged-in-as')
        @include('includes.partials.announcements')

        @include('includes.partials.messages')
		        	<div class="branding text-center">
			        	<img class="logo" style="max-width: 80%; height: 100px;" src="{{ asset('img/2021-04-Outreach-234x50px-SVG.svg') }}" />
		        	</div>
                <p>&nbsp;</p>

				<ul class="nav justify-content-center">
                @auth
                    @if ($logged_in_user->isUser())
	                    <li class="nav-item"><a class="nav-link btn btn-outline-primary m-2" href="{{ route('frontend.user.account') }}">@lang('Account')</a></li>

                        <li class="nav-item"><a class="nav-link btn btn-primary m-2" href="{{ route('frontend.user.dashboard') }}">@lang('Dashboard')</a></li>
                    @endif
                    @if ($logged_in_user->isAdmin())
	                    <li class="nav-item"><a class="nav-link btn btn-outline-primary m-2" href="{{ route('frontend.user.account') }}">@lang('Account')</a></li>

                        <li class="nav-item"><a class="nav-link btn btn-primary m-2" href="{{ route('admin.dashboard') }}">@lang('Dashboard')</a></li>
                    @endif
                @else
                    @if (config('boilerplate.access.user.registration'))
                        <li class="nav-item"><a class="nav-link btn btn-outline-primary m-2" href="{{ route('frontend.auth.register') }}">@lang('Create Account')</a></li>
                    @endif

                    <li class="nav-item"><a class="nav-link btn btn-primary m-2" href="{{ route('frontend.auth.login') }}">@lang('Sign In')</a></li>
                @endauth
                </ul>
                

                <p>&nbsp;</p>
                <p>&nbsp;</p>
                </div>
            </div><!--content-->
        </div><!--app-->

        @stack('before-scripts')
        <script src="{{ mix('js/manifest.js') }}"></script>
        @stack('after-scripts')
    </body>
</html>
