
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
                            <input type="text" name='nome' placeholder='Nome' value="{{ old("nome") }}">
                        </p>
                        <p>
                            <input type="text" name='cognome' placeholder='Cognome' value="{{ old("cognome") }}">
                        </p>
                        <p>
                            <input type="text" name='email' placeholder='Email' value="{{ old("email") }}">
                        </p>
                        <p>
                            <input type="password" name='password' placeholder='Password' value="{{ old("password") }}">
                        </p>
                        <p>
                            <input type="password" name='ripetiPassword' placeholder='Ripeti password'>
                        </p>
                        <p>
                            <label>&nbsp <input class='btn-registrazione' type="submit"></label>
                        </p>
                    </form>
                </div>
                <a class="navigate" href="{{ url('login') }}">Login</a>
            </section>
        </article>
    </body>
</html>