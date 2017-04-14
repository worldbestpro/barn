@extends('layouts.default')

<!-- Main Content -->
@section('content')

    <body class="hold-transition login-page bg-green">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>HUTCH</b></a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Password reset</p>
            <form role="form" method="POST" id="password_reset" action="{{ url('/password/email') }}">
                {!! csrf_field() !!}

                @if (session('status'))
                    <div class="alert alert-info">
                        {{ session('status') }}
                    </div>

                @else

                    @if ($errors->has('email'))
                        <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                    <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" placeholder="Email" name="email" class="form-control">
                        <span class="fa fa-envelope form-control-feedback"></span>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-success btn-block btn-flat" type="submit">Send password reset link</button>
                        </div><!-- /.col -->
                    </div>

                @endif
            </form>

            <!-- /.social-auth-links -->

            <div class="row">
                <div class="col-xs-12 text-center">
                    <br>
                    <a class="text-center" href="{{ url('/') }}">Return to sign in</a>
                    <br>
                    <a class="text-center" href="{{ url('/register') }}">Register a new membership</a>
                </div><!-- /.col -->
            </div>


        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

@endsection
