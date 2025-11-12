@extends('app')

@section('content')

    <section class="container" id="register">

        <div class="col-12 col-md-8 rounded-5 shadow-lg p-5">

            <div class="mx-auto">
            </div>

            <div class="row row-gap-3 align-items-center">

                <div class="col-12 col-md-6">

                    <h1 class="fw-bold mb-5 text-center text-md-start">Rejestracja</h1>
                    <form action="{{ route('store') }}" method="POST" class="register-form" >

                    @csrf
                    <div class="mb-4 @error('first_name') danger @enderror">
                        <label for="first_name">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="*Imię" >
                        </label>
                        @if ($errors->has('first_name'))
                            <ul>
                            @foreach ($errors->get('first_name') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="mb-4 @error('last_name') danger @enderror">
                        <label for="last_name">
                            <i class="fa-solid fa-id-card"></i>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="*Nazwisko">
                        </label>
                        @if ($errors->has('last_name'))
                            <ul>
                            @foreach ($errors->get('last_name') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        @endif
                    </div>
                        <div class="mb-4 @error('name') danger @enderror">
                            <label for="name">
                                <i class="fa-solid fa-id-card"></i>
                                <input type="text" class="form-control" id="name" name="name" placeholder="*Nazwa użytkownika">
                            </label>
                            @if ($errors->has('name'))
                                <ul>
                                    @foreach ($errors->get('name') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    <div class="mb-4 @error('email') danger @enderror">
                        <label for="email">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="text" class="form-control" id="email" name="email" placeholder="*Email">
                        </label>
                        @if ($errors->has('email'))
                            <ul>
                            @foreach ($errors->get('email') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="mb-4 @error('password') danger @enderror">
                        <label for="password">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" class="form-control" id="password" name="password" placeholder="*Hasło">
                        </label>
                        @if ($errors->has('password'))
                            <ul>
                            @foreach ($errors->get('password') as $error)
                                    <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="mb-4 @error('password_confirmation') danger @enderror">
                        <label for="password_confirmation">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="*Powtórz hasło">
                        </label>
                        @if ($errors->has('password_confirmation'))
                            <ul>
                            @foreach ($errors->get('password_confirmation') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="d-flex justify-content-center justify-content-md-start">
                        <button type="submit" class="btn btn-primary">Zarejestruj się</button>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('login') }}" class="d-inline-block mb-2">Posiadasz już konto? Zaloguj się</a><br/>
                        <a href="{{ route('verify_email') }}">Posiadasz konto, ale nie jest zweryfikowane? Zweryfikuj się</a>
                    </div>

                    </form>

                </div>

                <div class="col-12 col-md-6">
                    <img src="{{ asset('images/form.png') }}" width="100%">
                </div>

            </div>

        </div>

    </section>

@endsection
