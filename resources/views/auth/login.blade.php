@extends('layouts.auth')

@section('content')

<div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">
                
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0" >
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <!--  -->
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <!-- Login Page -->
                            <div class="col-lg-6" id="login-page">
                                <div class="p-3 d-flex justify-content-center">
                                    @if($pt)
                                    <div class="alert alert-primary text-center" role="alert">
                                        <h5 class="m-0">Now Open!</h5>
                                        <p class="m-0"><strong>SDTL {{$pt->sdtl}} CYCLE {{$pt->cycle}}</strong></p>
                                        <p class="m-0"><strong>Remaining Slot: {{$pt->application_limit}}</strong></p>
                                    </div>
                                    @endif
                                </div>
                                <div class="px-5 pb-5">
                                    <h3 class="text-center">National Reference Laboratory <br>East Avenue Medical Center (NRL-EAMC)</h3>
                                    <hr>
                                    <form class="user" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="form-group">
                                            <input id="email" type="text" class="form-control  form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email or Username">

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

                                        <div class="d-flex flex-column flex-sm-row align-items-start">

                                            @if (Route::has('password.request'))
                                            <a class="btn btn-link" style="text-align:left" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                            @endif
                                            <a class="btn btn-link" onclick="handleChangePage()">
                                                {{ __('if you are a new DT facility') }}
                                            </a>
                                        </div>

                                    </form>
                                </div>
                            </div>
                            <!-- Register new DT Page -->
                            <div class="card-body p-0" style="display: none;" id="register-page-instruction">
                                @include('includes.registrationInstruction')
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>


@endsection

@section('javascript')
<script>
    window.onload = function() {
        const newPtModal = $('#new-pt-modal')
        newPtModal.modal()
        
    }
    const loginPage = $('#login-page')
    const registerPageInstruction = $('#register-page-instruction');
    // registerPageInstruction.hide()
    const handleChangePage = function(){
        loginPage.toggle(100)
        registerPageInstruction.toggle()
    }
</script>
@endsection