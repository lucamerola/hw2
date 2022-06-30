@extends('layouts.guest')

@section('title', '| Registrazione')

@section('script')
<script src='{{ asset('js/register.js') }}' defer></script>
<script type="text/javascript">
    const REGISTER_ROUTE = "{{route('register')}}";
</script>
@endsection


@section('content')
<h1>Presentati</h1>

<form name='signup' method='post' enctype="multipart/form-data" autocomplete="off" action="{{ route('register') }}">
    @csrf
    <div class="names">
        <div class="name @error('nome') errorj @enderror">
            <div><label for='name'>Nome</label></div>
            <div><input type='text' name='nome' value='{{ old('nome') }}'></div>
            <span>Nome strano</span>
        </div>
        <div class="surname @error('cognome') errorj @enderror">
            <div><label for='name'>Cognome</label></div>
            <div><input type='text' name='cognome' value='{{ old('cognome') }}'></div>
            <span>Cognome strano</span>
        </div>
    </div>
    <div class="email @error('email') errorj @enderror">
        <div><label for='email'>Email</label></div>
        <div><input type='text' name='email' value='{{ old('email') }}' autocomplete="email"></div>
        <span>&nbsp;@error('email') {{ $message }} @enderror</span>
    </div>
    <div class="password @error('password') errorj @enderror">
        <div><label for='password'>Password</label></div>
        <div><input type='password' name='password'></div>
        <span>&nbsp;@error('password') {{ $message }} @enderror</span>
    </div>
    <div class="confirm_password @error('password') errorj @enderror">
        <div><label for='password_confirmation'>Conferma Password</label></div>
        <div><input type='password' name='password_confirmation'></div>
        <span>&nbsp;</span>
    </div>
    <div class="fileupload @error('avatar') errorj @enderror">
        <div><label for='avatar'>Scegli un'immagine profilo</label></div>
        <div>
            <input type='file' name='avatar' accept='.jpg, .jpeg, image/gif, image/png' id="upload_original">
            <div id="upload"><div class="file_name">Seleziona un file...</div><div class="file_size"></div></div>
        </div>
        <span>&nbsp;@error('avatar') {{ $message }} @enderror</span>
    </div>
    <div class="allow @error('allow') errorj @enderror"> 
        <div><input type='checkbox' name='allow' value="1" {{ (! empty(old('allow')) ? 'checked' : '') }}></div>
        <div><label for='allow'>Acconsento al furto dei dati personali</label></div>
    </div>
    <div class="submit">
        <input type='submit' value="Registrati" id="submit" {{-- disabled --}}>
    </div>
</form>
<div class="signup">Hai un account? <a href="{{ route('login') }}">Accedi</a>
@endsection


