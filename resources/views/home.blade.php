<!DOCTYPE html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css">
    </head>
    <body>
        <nav class="navbar navbar-default" data-spy="affix" style="background-color:#fb9013">
            <div class="navbar-header">
                <span>
                    <img src="https://happimobiles.com/wp-content/uploads/2019/03/Page-1-Copy-2.png" width="150" height="50">
                </span>
            </div>
            <div class="navbar-nav ml-auto" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <!-- Add href to login page-->
                    <li><a style="color: #ffffff;" href="{{ url('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <span class="glyphicon glyphicon-log-out"></span>{{ __('Logout') }}
                        </a>
                    </li>
                    <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="text" name="shop" value="{{ $shop }}" hidden>
                    </form>
                </ul>
            </div>
        </nav>
        <div class="text-left" role="alert">
            <h4>Welcome {{ Auth::user()->name }}</h4>
        </div>
        <div class="jumbotron text-center">
            <h3>{{ Auth::user()->points }}</h3></br>
            <!-- On click to send mail-->
            @if(Auth::user()->redeme)
            <input class="form-control" type="text" value="{{ $voucher }}" disabled>
            @else
            <a href="{{ url($shop.'/redeme') }}"><button type="button" class="btn btn-info" >Redeem Points</button></a>
            @endif
        </div>
        <h4 class="text-center">Complete Tasks to Earn Points</h4>
        <br>
        <div class="text-center col-4 mx-auto" style="color:#ffffff">
            <!--On click to facebook-->
            <a href="{{ url($shop.'/task/facebook') }}" class="btn btn-block btn-social btn-facebook"
            @if(Auth::user()->facebook_like == 1)
            onclick="return false;"
            @endif
            ><span class="fa fa-facebook"></span>Like us on Facebook</a>
            <!--On click to Google reviews-->
            <a href="{{ url($shop.'/task/google') }}" class="btn btn-block btn-social btn-google"
            @if(Auth::user()->google_like == 1)
            onclick="return false;"
            @endif
            ><span class="fa fa-google"></span>Review us on Google</a>
            <!--On click to follow on Instagram-->
            <a href="{{ url($shop.'/task/instagram') }}" class="btn btn-block btn-social btn-foursquare"
            @if(Auth::user()->instagram_like == 1)
            onclick="return false;"
            @endif
            ><span class="fa fa-instagram"></span>Follow us on Instagram</a>
            <!--On click to Subscribe on Youtube-->
            <?php
            // <a href="{{ url($shop.'/task/youtube') }}" class="btn btn-block btn-social btn-pinterest"
            // @if(Auth::user()->youtube_like == 1)
            // onclick="return false;"
            // @endif
            // ><span class="fa fa-youtube"></span>Subscribe on Youtube</a>
            ?>
        </div>
    </body>
</html>
