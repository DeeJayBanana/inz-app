@extends('app')

@section('panel')

    <div class="container-fluid" id="account">

        <div class="col-12 col-md-3">

            <h3 class="title mb-3">Dane</h3>
            <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data" class="row">
                @csrf
                @method('PUT')
                <div class="col-6 mb-3">
                    <label for="first_name" class="form-label">Imię:</label>
                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Imię:" value="{{ Auth()->user()->first_name }}">
                </div>
                <div class="col-6 mb-3">
                    <label for="last_name" class="form-label">Nazwisko:</label>
                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Nazwisko:" value="{{ Auth()->user()->last_name }}">
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nazwa użytkownika:</label>
                    <input type="text" class="form-control" id="name" placeholder="Nazwa użytkownika:" value="{{ Auth()->user()->name }}" disabled readonly>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Email:" value="{{ Auth()->user()->email }}" disabled readonly>
                </div>
                <div class="mb-3">
                    <label for="avatar" class="form-label">Awatar:</label>
                    <input class="form-control form-control-sm" name="avatar" id="avatar" type="file">
                </div>

                <div>
                    <button type="submit" class="btn btn-custom w-auto">Zapisz zmiany</button>
                </div>
            </form>
        </div>

    </div>

@endsection

