<div id="total-menu">
    <nav id="nav-bar" class="hidden">
        <div id="div-close">
            <img src="{{ url("img/cancel.png") }}" alt="img-close-menu">
        </div>
        <div id="title">
            <a href="{{ url("/") }}"><h3>ArtInCocktail</h3></a>
        </div>
        <div id="menu-bar">
            <a href="{{ url("/") }}">Home</a>
            <a href="{{ url("preferiti") }}">Preferiti</a>
        </div>
        <div id="div-user">
            <div id="div-avatar">
                <img src="{{ url("img/avatar.png") }}" alt="img-avatar">
            </div>
            <div class="hidden" id="menu-tendina">
                @if ( Session::has('user_id') )
                    <a href='{{ url("logout") }}'>Logout</a>
                @else
                <a href='{{ url("login") }}'>Accedi</a>
                <a href='{{ url("register") }}'>Registrati</a>
                @endif
            </div>
        </div>
        <!--
        <div id="title">
            <a href="/"><h1>ArtInCocktail</h1></a>
        </div>-->
    </nav>

    <nav id="central-bar">
        <div id="div-open-menu">
            <img src="{{ url("img/hamburger2.png") }}" alt="img-hamburger">
        </div>
        <div id="title-central-bar">
            <a href="{{ url("/") }}"><h1>ArtInCocktail</h1></a>
        </div>
    </nav>
</div>