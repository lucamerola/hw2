<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ url('css/preferiti.css') }}">
    <link rel="stylesheet" href="{{ url('css/menu.css') }}">
    <link rel="stylesheet" href="{{ url('css/fonts.css') }}">
    <script src="{{ url('js/menu.js') }}" defer="true"></script>
    <script src="{{ url('js/preferiti.js') }}" defer="true"></script>
    <title>Home</title>
</head>
<body>
    <div id="div-instagram">
        <a href="https://www.instagram.com/artincocktail/" target="_blank"><img src="{{ url('img/logo-instagram.png')}}" alt="logo-instagram"></a>
    </div>
    @extends('piecesCodePhp/menu')
    <article id="contenuto">
            
        <header>
            <div id="container-header">
                <div id="text-box-header">
                    <h2>Preferiti</h2>
                </div>
                <div id="overlay"></div>
                <img id="img-header" src="{{ url('img/img-header.png') }}" alt="img-header">
            </div>
        </header>
        <section>
            <div id="lista-cocktail-preferiti">
                
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