<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ url("css/menu.css") }}">
    <link rel="stylesheet" href="{{ url("css/fonts.css") }}">
    <link rel="stylesheet" href="{{ url("css/cocktail.css") }}">
    <script src="{{ url("js/menu.js") }}" defer="true"></script>
    <script src="{{ url("js/cocktail.js") }}" defer="true"></script>
    <script>
        const BASE_URL = "{{ url('/') }}/";
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cocktail</title>
</head>
<body>
    <div id="div-instagram">
        <a href="https://www.instagram.com/artincocktail/" target="_blank"><img src="{{ url("img/logo-instagram.png") }}" alt="logo-instagram"></a>
    </div>
    @extends('piecesCodePhp/menu')
    <article id="contenuto">
            
        <header>
            <!--
            <div id="container-header">
                <div id="text-box-header">
                    <h2>Cocktail</h2>
                </div>
                <div id="overlay"></div>
                <img id="img-header" src="{{ url("img/img-header.png") }}" alt="img-header">
            </div>
            -->
        </header>
        <section>

            <div id="container-section-header">
                <div class="container-title">
                    <h2>{{ $nome }}</h2>
                </div>
                <div class="container-cocktail">
                    <div class="div-container-img">
                        <img id="img-cocktail" src="{{ $urlImg }}" alt="img-cocktail">
                        
                    </div>
                    <div class="div-container-option">
                        <div class="div-like">
                            @if ($like==true)
                                <img class="img-like" src="/img/like.png" alt="img-like">
                            @else
                                <img class="img-like" src="/img/dislike.png" alt="img-like">
                            @endif
                            @if ($conteggioLike>0)
                                <h1 class="conteggio">{{ $conteggioLike }}</h1>
                            @else
                                <h1 class="conteggio"></h1>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div id="container-section-footer">
                @if ( Session::has('user_id') )
                <div class="new-review">
                    <h1>Scrivi una recensione</h1>
                    <form name="form-review">
                        @csrf
                        <p>
                            <textarea name='Testo' class="textBox" placeholder="Inserisci qui la tua recensione" id="testo"></textarea>
                        </p>
                        <p>
                            <label>&nbsp <input class='btn-send-review' type="submit" value="Invia recensione"></label>
                        </p>
                    </form>
                </div>
                @endif
                <h3>Recensioni</h3>
                <div class="reviews">
                    <!--
                    <div class="review-card">
                        <div class="img-user">
                            <img src="{{ url("img/avatar.png") }}" alt="img-avatar">
                        </div>
                        <div class="information">
                            <div class="dati-utente">
                                Luca Merola
                            </div>
                            <div class="review-user">
                                <p>
                                    Questa è una recensionee
                                </p>
                            </div>
                        </div>
                        <div class="trash">
                            <img src="/img/delete.png" alt="delete"> 
                        </div>
                    </div>
                    <div class="review-card">
                        <div class="img-user">
                            <img src="{{ url("img/avatar.png") }}" alt="img-avatar">
                        </div>
                        <div class="information">
                            <div class="dati-utente">
                                Luca Merola
                            </div>
                            <div class="review-user">
                                <p>
                                    Questa è una recensioneeeeeee eeeeeeeeeeeee eeeeeeeeeeeee eeeeeeeeeeeeeeee eeeee eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee eeeeeeeeeeeeeeeeeeeeeeeeeeee eeeeeeeeeeeeeeeeeeeeee eeeeeeeee
                                </p>
                            </div>
                        </div>
                        <div class="trash">
                            <img src="/img/delete.png" alt="delete"> 
                        </div>
                        
                    </div>
                    -->
                </div>
            </div>
            
        </section>
        <br>
        <footer>
            <h3 class="Autore">
                Nome: Luca Merola 
                <br>
                Matricola: O46002231
            </h3>
        </footer>
    </article>

</body>
</html>