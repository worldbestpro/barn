@extends('layouts.default')

@section('content')

    <body class="hold-transition login-page bg-green">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>HUTCH</b></a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Register in HUTCH</p>
            <form role="form" method="POST" action="{{ url('/register') }}">
                {!! csrf_field() !!}


                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
                <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                    <input type="text" placeholder="Full Name" name="name" value="{!! old('name') !!}" class="form-control">
                </div>


                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" placeholder="Email" name="email" value="{!! old('email') !!}" class="form-control">
                    <span class="fa fa-envelope form-control-feedback"></span>
                </div>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" name="password" placeholder="Password" class="form-control">
                    <span class="fa fa-lock form-control-feedback"></span>
                </div>
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
                <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <input type="password" name="password_confirmation" placeholder="Password confirmation" class="form-control">
                    <span class="fa fa-lock form-control-feedback"></span>
                </div>



                {{--   ----------------------   --}}


                <div class="row">
                    <div class="col-xs-8">
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button class="btn btn-success btn-block btn-flat" type="submit">Register</button>
                    </div><!-- /.col -->
                </div>
            </form>

            <!-- /.social-auth-links -->

            <a href="{{ url('/password/reset') }}">I forgot my password</a><br>
            <a class="text-center" href="{{ url('/') }}">I already have an account</a>

        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

@endsection
