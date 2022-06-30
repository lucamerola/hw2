
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ url('css/menu.css') }}">
        <link rel="stylesheet" href="{{ url('css/fonts.css') }}">
        <link rel="stylesheet" href="{{ url('css/login.css') }}">
        <script src="{{ url('js/menu.js') }}" defer="true"></script>
        <title>Accesso</title>
    </head>
    <body>
        <?php /*include("menu.php");*/ ?>
        @extends('piecesCodePhp/menu')
        <section id="contenuto">
            <div id="contenuto-header">
                <h2>Accesso</h2>
            </div>
            @if($error!=null)
                @foreach($error as $err)
                    <section class="error">{{$err}}.</section>
                @endforeach
            @endif
            <div id="div-accesso">
                <form name="form-accesso" method="post">
                    @csrf
                    <p>
                        <label>E-mail <input type="text" name='email' value="{{ old("email") }}"></label>
                    </p>
                    <p>
                        <label>Password <input type="password" name='password'></label>
                    </p>
                    <p>
                        <label>&nbsp <input type="submit"></label>
                    </p>
                </form>
            </div>
            <a class="navigate" href="{{ url('register') }}">Registrazione</a>
        </section>
    </body>
</html>