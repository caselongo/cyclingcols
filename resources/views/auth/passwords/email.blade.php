@extends('layouts.master')

@section('content')
<div class="container py-4 font-weight-light">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-4">
            <div class="card shadow-sm">
                <div class="card-body">
					<div class="text-center mb-3">
						<h4 class="font-weight-light">{{ __('Reset Password') }}</h4>
					</div>
                    @if (session('status'))
                        <div class="col-8 offset-2 alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-8 offset-2">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="E-mail">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-8 offset-2 mb-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Send Password Reset Link') }}
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
