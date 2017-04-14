@extends('layouts.default')

@section('content')


    <body class="hold-transition login-page bg-green">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>HUTCH</b></a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Register in HUTCH</p>
            <form role="form" method="POST" action="{{ url('/password/reset') }}">
                {!! csrf_field() !!}


                <input type="hidden" name="token" value="{{ $token }}">


                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" placeholder="Email" name="email" value="{{ $email or old('email') }}" class="form-control">
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



                <div class="row">
                    <div class="col-xs-8">
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button class="btn btn-success btn-block btn-flat" type="submit">Reset</button>
                    </div><!-- /.col -->
                </div>
            </form>

            <!-- /.social-auth-links -->

            {{--<a href="{{ url('/password/reset') }}">I forgot my password</a><br>--}}
            {{--<a class="text-center" href="{{ url('/login') }}">I already have an account</a>--}}

        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->


@endsection
