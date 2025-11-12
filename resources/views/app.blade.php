<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('scripts')
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

            <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel"><a href="/panel" class="text-dark text-decoration-none">AI Video Analyser</a></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                </div>
            </div>

        </div>

        <div class="col-12 col-md-2 border-end d-none d-lg-block" style="height: 100vh;">

            <span class="d-block text-center p-3 fs-3 lh-1 fw-bold" style="height: 10vh;"><a href="/panel" class="text-dark text-decoration-none">AI Video<br/>Analyser</a></span>

            <div class="p-3 menu-content">

                <div class="d-flex flex-column justify-content-between mt-3" style="height: 85vh;">

                    <div class="menu">
                        <span class="text-muted">ADMIN</span>
                        <ul>
                            <li><a href="/panel/users"><i class="fa-solid fa-users"></i> Użytkownicy</a></li>
                        </ul>
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

</body>
</html>
