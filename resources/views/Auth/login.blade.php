@extends('app')

@section('content')

    <section class="container" id="login">

        <div class="col-12 col-md-6 rounded-5 shadow-lg p-5">

            <div class="row row-gap-3 align-items-center">

                <div class="col-12 col-md-6">
                    <img src="{{ asset('images/form.png') }}" width="100%">
                </div>

                <div class="col-12 col-md-6">

                    <h1 class="fw-bold mb-5 text-center text-md-start">Zaloguj się</h1>
                    <form action="{{ route('login') }}" method="POST" class="register-form" >

                    @csrf
                    <div class="mb-4 @error ('login') danger @enderror">
                        <label for="email">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" class="form-control" id="email" name="email" placeholder="*Email">
                        </label>
                    </div>
                    <div class="mb-4 @error ('login') danger @enderror">
                        <label for="password">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" class="form-control" id="password" name="password" placeholder="*Hasło">
                        </label>
                        @if($errors->has('login'))
                            <ul>
                                @foreach($errors->get('login') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="d-flex justify-content-center justify-content-md-start">
                        <button type="submit" class="btn btn-primary">Zaloguj się</button>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('register') }}" class="d-inline-block mb-2">Nie posiadasz konta? Zarejestruj się</a><br/>
                        <a href="{{ route('verify_email') }}">Posiadasz konto, ale nie jest zweryfikowane? Zweryfikuj się</a>
                    </div>

                    </form>

                </div>

            </div>


        </div>

    </section>

@endsection
