@extends('app')

@section('content')

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Logowanie</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Rejestracja</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

@endsection
