@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
<div class="card-header bg-img"> 
    <div class="bg-overlay"></div>
    <h3 class="text-center m-t-10 text-white"> <strong> Reset Password </strong> </h3>
</div> 

<div class="card-body">
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <div class="col-md-12">
                <input class="form-control input-lg @error('email') is-invalid @enderror" type="text" required="" name="email" value="{{ $email ?? old('email') }}" placeholder="Email Address" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div class="col-12">
                <input class="form-control input-lg @error('password') is-invalid @enderror" type="password" name="password" required="" autocomplete="current-password"  placeholder="Password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div class="col-12">
                <input type="password" class="form-control input-lg" name="password_confirmation" required autocomplete="password" placeholder="Confirm Password">
            </div>
        </div>

        <div class="form-group text-center m-t-40">
            <div class="col-12">
                <button class="btn btn-primary btn-lg w-lg waves-effect waves-light" type="submit">Reset Password</button>
            </div>
        </div>
    </form>
</div>
@endsection
