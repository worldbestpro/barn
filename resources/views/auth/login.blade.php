@extends('layouts.default')

@section('content')
    <body class="hold-transition login-page bg-green">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>HUTCH</b></a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Please Sign In</p>
            <form role="form" method="POST" id="login-form">

                @if( session('csrf_error'))
                <div class="alert alert-warning" id="ajax-errors">
                    {{ session('csrf_error') }}
                </div>
                @endif
                {!! csrf_field() !!}

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" placeholder="Email" name="email" id="email" class="form-control">
                    <span class="fa fa-envelope form-control-feedback"></span>
                </div>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" name="password" id="passwd" placeholder="Password" class="form-control">
                    <span class="fa fa-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <label>
                            <input type="checkbox" name="rememberme" value="1" checked> Remember Me
                        </label>
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button class="btn btn-success btn-block btn-flat" type="submit">Sign In</button>
                    </div><!-- /.col -->
                </div>
            </form>

            <!-- /.social-auth-links -->

            <a href="{{ url('/password/reset') }}">I forgot my password</a><br>
            <a class="text-center" href="{{ url('/register') }}">Register a new membership</a>

        </div><!-- /.login-box-body -->

        <div class="alert alert-warning hide-online">
            <b>You currently is offline. You can login only if you previously have logged in on this computer with specified account.</b>
        </div>
    </div><!-- /.login-box -->

@endsection


@section('scripts')
    <script>$(App.Login);</script>
@endsection