@extends('layouts.auth')

@section('content')
<form class="user" method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group">
        <input id="email" type="email" class="form-control  form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Username">

        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group">
        <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <div class="custom-control custom-checkbox small form-check">
            <input class="custom-control-input" type="checkbox" name="remember" id="customCheck" {{ old('remember') ? 'checked' : '' }}>

            <label class="custom-control-label" for="customCheck">
                {{ __('Remember Me') }}
            </label>
            
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-user btn-block">
        {{ __('Login') }}
    </button>

    @if (Route::has('password.request'))
        <a class="btn btn-link" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
    @endif
  
</form>
@endsection

@section('javascript')
<script>
    window.onload = function(){

        $('#email').val('admin@gmail.com')
        $('#password').val('123')
    }
</script>
@endsection
