<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title --}}
    <title>{{ config('app.name', 'InstagramClone') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
{{--    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300" />--}}

    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('dist/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dist/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <!-- Header section -->
    <nav class="navbar fixed-top navbar-expand-md navbar-light bg-white shadow-sm" style="height: 57px">
        <div class="container">
            <!-- Logo -->
            <a href="{{ route('post.index') }}" class="navbar-brand">
                <img src="{{asset('img/insta.png')}}" alt="Instagram Logo" style="width: 132px;">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar5">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Links -->
            <div class="navbar-collapse collapse justify-content-stretch" id="navbar5">
                <!--begin::Main wrapper-->
                <div class="navbar-collapse collapse justify-content-stretch border-5" id="navbar5"
                    id="kt_docs_search_handler_basic"
                    data-kt-search-keypress="true"
                    data-kt-search-min-length="2"
                    data-kt-search-enter="true"
                    data-kt-search-layout="inline">
                    <!--begin::Input Form-->
                    <form data-kt-search-element="form"  action="{{ route('profile.search') }}" method="POST" role="search" class="m-auto d-inline w-80"  autocomplete="off">
                        @csrf
                        <!--begin::Hidden input(Added to disable form autocomplete)-->
                        <input type="hidden"/>
                        <!--end::Hidden input-->
                        <!--begin::Icon-->
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-2 svg-icon-lg-1 svg-icon-gray-500 position-absolute top-50 ms-5 translate-middle-y">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
                            </svg>
                        </span>
                        <!--end::Icon-->
                        <!--begin::Input-->
                        <input type="text" name="q" class="form-control form-control-lg form-control-solid px-15"
                               name="search"
                               value=""
                               placeholder="Search something"
                               data-kt-search-element="input"/>
                        <!--end::Input-->
                        <!--begin::Spinner-->
                        <span class="position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5" data-kt-search-element="spinner">
                        <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
                        </span>
                        <!--end::Spinner-->
                        <!--begin::Reset-->
                        <span class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 me-5 d-none"
                              data-kt-search-element="clear">
                        </span>
                        <!--end::Reset-->
                        </form>
                        <!--end::Form-->
                    <!--begin::Wrapper-->
                    <div class="py-5">
                        <!--end::Suggestion wrapper-->
                        <!--begin::Search results-->
                        <div data-kt-search-element="results" class="d-none">
                            ...
                        </div>
                        <!--end::Search results-->
                        <!--begin::Empty search-->
                        <div data-kt-search-element="empty" class="text-center d-none">
                            ...
                        </div>
                        <!--end::Empty search-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Main wrapper-->
                <ul class="navbar-nav" style="padding-top: 7px">
                    <li class="nav-item">

                    </li>
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item px-2 pt-1 {{ Route::is('post.index') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('post.index') }}">
                                <i class="fas fa-home fa-2x"></i>
                            </a>
                        </li>
                        <li class="nav-item px-2 pt-1 {{ Route::is('post.explore') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('post.explore') }}">
                                <i class="far fa-compass fa-2x"></i>
                            </a>
                        </li>
                        <li class="nav-item pl-2 pb-2">
                            <a href="{{route('profile.index', Auth::user()->username) }}" class="nav-link" style="height: 37px; width: 37px" >
                                <img src="{{ asset(Auth::user()->profile->getProfileImage()) }} " class="rounded-circle w-100">
                            </a>
                        </li>
                        <li class="nav-item dropdown pt-1">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre></a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                @can('update', Auth::user()->profile)
                                    <a class="dropdown-item" href="{{ route('post.create') }}" role="button">
                                        Add New Post
                                    </a>
                                @endcan
                                    @can('update', Auth::user()->profile)
                                        <a class="dropdown-item" href="{{ route('stories.create') }}" role="button">
                                            Add New Story
                                        </a>
                                    @endcan
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        {{-- @endif --}}
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <div class="pt-3 mt-5">
        @yield('content')
    </div>
</div>

@yield('exscript')
<!--begin::Javascript-->
<!--begin::Global Javascript Bundle(used by all pages)-->
<script src="{{ asset('dist/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('dist/assets/js/scripts.bundle.js') }}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Page Custom Javascript(used by this page)-->
<script src="{{ asset('dist/assets/js/custom/widgets.js') }}"></script>
<script src="{{ asset('dist/assets/js/custom/apps/chat/chat.js') }}"></script>
<script src="{{ asset('dist/assets/js/custom/modals/create-app.js') }}"></script>
<script src="{{ asset('dist/assets/js/custom/modals/upgrade-plan.js') }}"></script>
<!--end::Page Custom Javascript-->
</body>

</html>

