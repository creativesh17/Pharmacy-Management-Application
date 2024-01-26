@extends('layouts.app')
@section('title', 'Log In')

@section('content')
<div class="card-header bg-img"> 
    <div class="bg-overlay"></div>
    <h3 class="text-center m-t-10 text-white"> <strong> Log In </strong> </h3>
</div> 


<div class="card-body">
    <form class="form-horizontal m-t-20" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <div class="col-12">
                <input class="form-control input-lg @error('email') is-invalid @enderror" type="text" required="" name="email" placeholder="Username or Email or Phone Number" autofocus>
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
                <div class="checkbox checkbox-primary">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
                
            </div>
        </div>
        
        <div class="form-group text-center m-t-40">
            <div class="col-12">
                <button class="btn btn-primary btn-lg w-lg waves-effect waves-light" type="submit">Log In</button>
            </div>
        </div>

        <div class="form-group row m-t-30">
            <div class="col-sm-7">
                <a href="{{ route('password.request') }}"><i class="fa fa-lock m-r-5"></i>  {{ __('Forgot Your Password?') }}</a>
            </div>
            <div class="col-sm-5 text-right">
                <a href="{{ route('register') }}">Create an account</a>
            </div>
        </div>
    </form> 
</div>  

@endsection

