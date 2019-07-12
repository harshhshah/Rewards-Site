<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Sign in') }}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        .login-form {
            width: 385px;
            margin: 30px auto;
        }
        .login-form form {
            margin-bottom: 15px;
            background: #f7f7f7;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }
        .login-form h2 {
            margin: 0 0 15px;
        }
        .form-control, .login-btn {
            min-height: 38px;
            border-radius: 2px;
        }
        .input-group-addon .fa {
            font-size: 18px;
        }
        .login-btn {
            font-size: 15px;
            font-weight: bold;
        }
        .social-btn .btn {
            border: none;
            margin: 10px 3px 0;
            opacity: 1;
        }
        .social-btn .btn:hover {
            opacity: 0.9;
        }
        .social-btn .btn-primary {
            background: #507cc0;
        }
        .social-btn .btn-info {
            background: #64ccf1;
        }
        .social-btn .btn-danger {
            background: #df4930;
        }
        .or-seperator {
            margin-top: 20px;
            text-align: center;
            border-top: 1px solid #ccc;
        }
        .or-seperator i {
            padding: 0 10px;
            background: #f7f7f7;
            position: relative;
            top: -11px;
            z-index: 1;
        }
    </style>
    </head>
    <body>
        <nav class="navbar" style="background-color:#fb9013 ;">
            <span>
                <img src="https://happimobiles.com/wp-content/uploads/2019/03/Page-1-Copy-2.png" width="150" height="50">
            </span>
        </nav>
        <div class="login-form">
            <!-- Add action -->
            <form method="post" action="{{ url('login') }}">
              @csrf
              <input type="text" name="shop" value="{{ $shop }}" hidden>
                <h2 class="text-center">{{ __('Sign in') }}</h2><br>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary login-btn btn-block" name="login_user">{{ __('Sign in') }}</button>
                </div>
                <div class="clearfix">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="pull-right">{{ __('Forgot Your Password?') }}</a>
                    @endif
                </div>
                <div class="or-seperator"><i>or</i></div>
                    <p class="text-center">Login with your social media account</p>
                    <div class="text-center social-btn">
                        <!-- Add href and onclick to Google login -->
                        <a href="{{ url($shop.'/login/google') }}" class="btn btn-danger" onclick=""><i class="fa fa-google"></i>&nbsp; Google</a>
                        <!-- Add href and onclick to Facebook login -->
                        <a href="{{ url($shop.'/login/facebook') }}" class="btn btn-primary" onclick=""><i class="fa fa-facebook"></i>&nbsp; Facebook</a>
                    </div>
            </form>
            <!-- Add href to Register page -->
            <p class="text-center">Don't have an account? <a href="{{ url($shop.'/register') }}">Sign up here!</a></p>
        </div>
    </body>
</html>
