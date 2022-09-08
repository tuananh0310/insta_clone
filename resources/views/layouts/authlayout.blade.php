<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title --}}
    <title>{{ config('app.name', 'InstagramClone') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
</head>
<body>
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            @yield('form')
            <div class="login100-more" style="background-image: url('{{ asset('img/tuananh.jpg') }}');"></div>
        </div>
    </div>
</div>

<script>
    document.querySelector('.login100-form').addEventListener('submit', function(e){
        var error = document.querySelector('.is-invalid');
        if(error) hideLoading();
        showLoading();
    })
    function showLoading() {
        document.querySelector('.spinner-border').classList.remove('d-none')
    }
    function hideLoading() {
        document.querySelector('.spinner-border').classList.add('d-none')
    }
</script>
</body>
</html>
