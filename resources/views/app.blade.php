<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <title></title>
</head>
<body>

<main>

    <div class="position-fixed top-0 start-50 translate-middle-x mt-5" style="z-index: 99999;">

        @foreach($alertTypes as $key => $value)
            @if(session($key))
                <div class="alert alert-{{ $value }}">{{ session($key) }}</div>
            @endif
        @endforeach

    </div>

    @auth

        <div class="wrapper">
            <header class="d-flex align-items-center justify-content-end px-3">
                <a href="/logout" class="fs-1 float-end"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
            </header>
            <section id="left-side">
                <div class="leftpanel-logo">
                    <a href="/panel" class="text-center"><img src="{{ asset('images/logo.png') }}" width="75%" alt=""></a>
                </div>
                <div class="leftpanel-content">
                    <div class="item_group">
                        <h2 class="group_title">Menu</h2>
                        <ul class="group_list">
                            <li class="{{request()->is('panel') ? 'active' : ''}}"><i class="fa-solid fa-house"></i> <a href="/panel">Strona główna</a></li>
                        </ul>
                    </div>
                    <div class="item_group">
                        <h2 class="group_title">Użytkownik</h2>
                        <ul class="group_list">
                            <li class="{{request()->is('panel/analyse') ? 'active' : ''}}"><i class="fa-solid fa-video"></i> <a href="/panel/analyse">Analiza Wideo</a></li>
                        </ul>
                    </div>

                    @if(auth()->user()->hasRole('admin'))
                    <div class="item_group">
                        <h2 class="group_title">Administrator</h2>
                        <ul class="group_list">
                            <li class="{{ request()->is('panel/admin/users') ? 'active' : '' }}"><i class="fa-solid fa-users"></i><a href="/panel/admin/users">Użytkownicy</a></li>
                            <li class="{{request()->is('panel/videos') ? 'active' : ''}}"><i class="fa-solid fa-video"></i><a href="/panel/videos">Wideo</a></li>
                        </ul>
                    </div>
                    @endif
                </div>
            </section>
            <section id="content">
                @yield('panel')
            </section>
        </div>

    @endauth

    @guest

        @yield('content')

    @endguest


</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
@stack('scripts')
</body>
</html>
