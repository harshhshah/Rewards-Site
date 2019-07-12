<!DOCTYPE html>
<html>
	<head>
		<title>{{ __('Register') }}</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
		<style type="text/css">
			body{
				color: #fff;
				font-family: 'Roboto', sans-serif;
			}
			.form-control{
				height: 40px;
				box-shadow: none;
				color: #000;
			}
			.form-control:focus{
				border-color: #fb9013;
			}
			.form-control, .btn{
				border-radius: 3px;
			}
			.signup-form{
				width: 400px;
				margin: 0 auto;
				padding: 30px 0;
			}
			.signup-form h2{
				color: #000;
				margin: 0 0 15px;
				position: relative;
				text-align: center;
			}
			.signup-form h2:before, .signup-form h2:after{
				content: "";
				height: 2px;
				width: 30%;
				background: #fb9013;
				position: absolute;
				top: 50%;
				z-index: 2;
			}
			.signup-form h2:before{
				left: 0;
			}
			.signup-form h2:after{
				right: 0;
			}
			.signup-form .hint-text{
				color: #999;
				margin-bottom: 30px;
				text-align: center;
			}
			.signup-form form{
				color: #000;
				border-radius: 3px;
				margin-bottom: 15px;
				background: #f2f3f7;
				box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
				padding: 30px;
			}
			.signup-form .form-group{
				margin-bottom: 20px;
			}
			.signup-form input[type="checkbox"]{
				margin-top: 3px;
			}
			.signup-form .btn{
				font-size: 16px;
				font-weight: bold;
				min-width: 140px;
				outline: none !important;
			}
			.signup-form .row div:first-child{
				padding-right: 10px;
			}
			.signup-form .row div:last-child{
				padding-left: 10px;
			}
			.signup-form a{
				color: #fff;
				text-decoration: underline;
			}
			.signup-form a:hover{
				text-decoration: none;
			}
			.signup-form form a{
				color:#5cb85c;
				text-decoration: none;

			}
			.signup-form form a:hover{
				text-decoration: underline;
			}
		</style>
	</head>
	<body>
		<nav class="navbar" style="background-color:#fb9013 ;">
   			<span>
   				<img src="https://happimobiles.com/wp-content/uploads/2019/03/Page-1-Copy-2.png" width="150" height="50">
   			</span>
  		</nav>
		<div class="signup-form">
			<!-- Add action -->
			<form method="post" action="{{ url('register') }}">
        @csrf
				<input type="text" name="shop" value="{{ $shop }}" hidden>
				<h2>{{ __('Register') }}</h2><br>
				<div class="form-group">
          <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Name">
				</div>
				<div class="form-group">
          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email Address" required="required" autocomplete="email">
        </div>
				<div class="form-group">
          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required="required" pattern=".{8,}" required title="8 characters minimum">
        </div>
				<div class="form-group">
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required="required" pattern=".{8,}" required title="8 characters minimum">
        </div>
        		<div class="form-group">
					<label class="checkbox-inline"><input type="checkbox" required="required"> I accept the <a href="https://happimobiles.com/terms-and-conditions/">Terms of Use</a> &amp; <a href="https://happimobiles.com/privacy-policy/">Privacy Policy</a></label>
				</div>
				<div class="form-group">
            <button type="submit" class="btn btn-success btn-lg btn-block">{{ __('Register Now') }}</button>
				</div>
				<!-- Add href to Login page -->
				<div class="text-center">Already have an account? <a href="{{ url($shop.'/login') }}" style="color:#507cc0" >Sign in</a></div>
    		</form>
		</div>
	</body>
</html>
