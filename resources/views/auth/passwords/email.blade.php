@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
<div class="card-header bg-img"> 
    <div class="bg-overlay"></div>
    <h3 class="text-center m-t-10 text-white"> <strong> Reset Password </strong> </h3>
</div> 

<div class="card-body">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <div class="col-md-12">
                <input class="form-control input-lg @error('email') is-invalid @enderror" type="text" required="" name="email" value="{{ old('email') }}" placeholder="Email Address" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group text-center m-t-40">
            <div class="col-12">
                <button class="btn btn-primary btn-lg w-lg waves-effect waves-light" type="submit">Send Password Reset Link</button>
            </div>
        </div>
    </form>
</div>
@endsection
