@extends('app')

@section('content')

    <section class="container" id="verify_email">

        <div class="col-12 col-md-6 rounded-5 shadow-lg p-5">

            <div class="row row-gap-3 align-items-center">

                <div class="col-12 col-md-6">
                    <img src="{{ asset('images/form.png') }}" width="100%">
                </div>

                <div class="col-12 col-md-6">

                    <h1 class="fw-bold mb-5 text-center text-md-start">Zweryfikuj się</h1>
                    <form action="{{ route('verification.send') }}" method="POST" class="register-form" >

                        @csrf
                        <div class="mb-4 @error ('login') danger @enderror">
                            <label for="email">
                                <i class="fa-solid fa-envelope"></i>
                                <input type="email" class="form-control" id="email" name="email" placeholder="*Email">
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
                            <button type="submit" class="btn btn-primary">Wyślij link weryfikacyjny</button>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('login') }}" class="d-inline-block mb-2">Posiadasz zweryfikowane konto? Zaloguj się</a>
                            <a href="{{ route('register') }}" >Nie posiadasz konta? Zarejestruj się</a>
                        </div>

                    </form>

                </div>

            </div>


        </div>

    </section>

@endsection
