@extends('app')


@section('panel')

    <div class="container">
        <div class="row text-center">
            <div class="col-12 col-lg-6">
                <div class="title-wrapper">
                    <h1 class="title">AI ANALYZER: Intelligent Match Highlights</h1>
                    <p class="desc">
                        AI Analyzer to innowacyjne narzędzie wykorzystujące sztuczną inteligencję do analizy materiałów wideo z meczów. Nasz system automatycznie identyfikuje i ekstrahuje najciekawsze fragmenty gry, takie jak bramki, asysty, kluczowe zagrania czy interwencje, tworząc dynamiczne skróty, które oszczędzają Twój czas i zapewniają dostęp do najważniejszych momentów każdego spotkania.
                    </p>
                </div>
                <div class="interactive-list">
                    <ul class="list-unstyled">
                        <li>
                            <div class="item">
                                <a href="/panel/analyse" class="d-flex flex-column align-items-center p-5 text-decoration-none">
                                    <img src="{{ asset('images/start.png') }}" width="50%" alt="">
                                    <h4 class="title mt-3">Zacznij Analizować</h4>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


@endsection

