@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="card-header bg-img">
    <div class="bg-overlay"></div>
   <h3 class="text-center m-t-10 text-white"> Create a new Account </h3>
</div>

<div class="card-body">
  <form class="form-horizontal m-t-20" action="{{ route('register') }}" method="post">
      @csrf
      <div class="form-group">
          <div class="col-12">
              <input id="name" type="text" class="form-control input-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Name">
              @error('name')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
      </div>
      <div class="form-group">
          <div class="col-12">
            <input id="name" type="text" class="form-control input-lg @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="Username">
            @error('username')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
      </div>
      <div class="form-group">
          <div class="col-12">
            <input id="email" type="email" class="form-control input-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
      </div>

      <div class="form-group">
          <div class="col-12">
            <input id="password" type="password" class="form-control input-lg @error('password') is-invalid @enderror" name="password" required autocomplete="password" placeholder="Password">
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

      <div class="form-group">
          <div class="col-12">
              <div class="checkbox checkbox-primary">
                  <input id="checkbox-signup" type="checkbox" checked="checked">
                  <label for="checkbox-signup">
                      I accept <a href="#">Terms and Conditions</a>
                  </label>
              </div>

          </div>
      </div>

      <div class="form-group text-center m-t-40">
          <div class="col-12">
              <button class="btn btn-primary waves-effect waves-light btn-lg w-lg" type="submit">Register</button>
          </div>
      </div>

      <div class="form-group m-t-30">
          <div class="col-sm-12 text-center">
              <a href="{{route('login')}}">Already have account?</a>
          </div>
      </div>
  </form>
</div>
@endsection
