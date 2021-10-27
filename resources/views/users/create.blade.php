@extends('layouts.main')

@section('content')


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        @if(isset($user->id))
            Update user details
        @else
            Create User
        @endif
    </h1>
    <a class="btn btn-danger" type="button" href="{{route('users.index')}}">Back</a>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @if(isset($user->id))
                        Update {{$user->name}} details
                    @else
                        Register User
                    @endif
                </div>

                <div class="card-body">
                    <form 
                        enctype="multipart/form-data"
                        method="POST" action="{{ isset($user->id) ? route('users.update', $user->id) : route('users.store') }}">
                        @csrf
                        @if(isset($user->id))
                        @method('PUT')
                        @endif

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>
                            <div class="col-md-6">
                                <select id="role" class="custom-select" name="role" required >
                                    <option value="">--Please select role here--</option>
                                    <option value="admin">Admin</option>
                                    <option value="encoder">Encoder</option>
                                    <option value="verifier">Verifier</option>
                                    <option value="head">Head</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="signature" class="col-md-4 col-form-label text-md-right">{{ __('Signature') }}</label>

                            <div class="col-md-6">
                                <input id="signature" type="file" class="form-control-file" name="signature" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    @if(isset($user->id))
                                        Update
                                    @else
                                        Create
                                    @endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('javascript')
@if(isset($user->id))
<script >
    window.onload = function(){
        $('#role').val('{{$user->roles->pluck('name')[0]}}')
    }
</script>
@endif
@endsection