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

    <div class="position-fixed top-0 start-50 translate-middle-x mt-5">

        @foreach($alertTypes as $key => $value)
            @if(session($key))
                <div class="alert alert-{{ $value }}">{{ session($key) }}</div>
            @endif
        @endforeach

    </div>

    @auth
    <div class="d-flex flex-wrap">

        <div class="container col-12 d-flex justify-content-between align-items-center d-lg-none mt-3">
            <span class="d-block text-center fs-3 lh-1 fw-bold"><a href="/panel" class="text-dark text-decoration-none">AI Video Analyser</a></span>
            <button class="btn btn-primary float-end" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"><i class="fa-solid fa-bars"></i></button>

            <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel" style="background-color:rgba(71,88,228)">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel"><a href="/panel" class="text-white text-decoration-none">AI VIDEO</a></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                </div>
            </div>

        </div>

        <div class="col-12 col-md-2 border-end d-none d-lg-block" style="height: 100vh;background-color:rgba(71,88,228)">

            <span class="d-block text-center p-3 fs-4 lh-1 fw-bold" style="height: 10vh;"><a href="/panel" class="text-white text-decoration-none">AI VIDEO</a></span>

            <div class="px-2 menu-content">

                <div class="d-flex flex-column justify-content-between mt-3" style="height: 85vh;">

                    <div class="menu">
                        <ul class="{{ request()->is('panel/analyse') ? 'active' : ''}}">
                            <li><a href="/panel/analyse"><i class="fa-solid fa-users"></i> Analiza</a></li>
                        </ul>
                        <div class="dropdown {{ request()->is('panel/admin*') ? 'active' : ''}}">
                            <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-lock"></i> Administrator</a>
                            <ul class="dropdown-menu">
                                <li><a href="/panel/admin/users" class="dropdown-item"><i class="fa-solid fa-users"></i> Użytkownicy</a></li>
                                <li><a href="/panel/videos" class="dropdown-item"><i class="fa-solid fa-users"></i> Video</a></li>
                            </ul>
                        </div>
                    </div>

                    <a href="/logout" class="btn btn-danger">Wyloguj</a>
                </div>

            </div>

        </div>

        <div class="col-12 col-md-10 container mt-5">

            @yield('panel')

        </div>


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
