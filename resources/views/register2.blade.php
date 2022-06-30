
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ url('css/menu.css') }}">
        <link rel="stylesheet" href="{{ url('css/registrazione.css') }}">
        <link rel="stylesheet" href="{{ url('css/fonts.css') }}">
        <script src="{{ url('js/registrazione.js') }}" defer="true"></script>
        <script src="{{ url('js/menu.js') }}" defer="true"></script>
        <title>Registrazione</title>
    </head>
    <body>
        <?php /*include("menu.php");*/ ?>
        @extends('piecesCodePhp/menu')
        <article>
            <section id="contenuto">
                <div id="contenuto-header">
                    <h2>Registrazione</h2>
                </div>
                @if($error!=null)
                    @foreach($error as $err)
                        <section class="error">{{$err}}.</section>
                    @endforeach
                @endif
                <div id="div-registrazione">
                    <form name="form-registrazione" method="post">
                        @csrf
                        <p>
                            <label>Nome <input type="text" name='nome' value="{{ old("nome") }}"></label>
                        </p>
                        <p>
                            <label>Cognome <input type="text" name='cognome' value="{{ old("cognome") }}"></label>
                        </p>
                        <p>
                            <label>E-mail <input type="text" name='email' value="{{ old("email") }}"></label>
                        </p>
                        <p>
                            <label>Password <input type="password" name='password' value="{{ old("password") }}"></label>
                        </p>
                        <p>
                            <label>Ripeti Password <input type="password" name='ripetiPassword'></label>
                        </p>
                        <p>
                            <label>&nbsp <input type="submit"></label>
                        </p>
                    </form>
                </div>
                <a class="navigate" href="{{ url('login') }}">Login</a>
            </section>
        </article>
    </body>
</html>