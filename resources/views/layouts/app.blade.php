<?php
use App\Setting;
?>

@include('shared.header')
<body>

<div id="app">
            
    @if(Ctm::isAgent('sp'))
        @include('shared.headNavSp')
    @else
        @include('shared.headNav')
    @endif

<div class="fix-wrap">
    
    {{-- @yield('belt') --}}
    

    
    <div class="container">
    
        <?php $className = isset($className) ? $className : ''; ?>
        
        <div class="pb-4 wrap-all clearfix {{ $className }}"><!-- offset-md-1-->
            @yield('bread')
            @yield('content')
            @yield('leftbar')
        </div>
    </div>

@include('shared.footer')
</div><!-- for sp-fix-wrap -->
</div><!-- id app -->

<?php
    $getNow = '?up=' . time();
?>

<!-- Scripts -->
{{-- integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" --}}

<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script type="text/javascript" src="//jpostal-1006.appspot.com/jquery.jpostal.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

@if(Request::is('shop/confirm'))
    @if(Setting::first()->is_product)
    	<script src="https://p01.mul-pay.jp/ext/js/token.js"></script>
    @else
    	<script src="https://pt01.mul-pay.jp/ext/js/token.js"></script>
    @endif
@endif

@if(! Ctm::isAgent('sp') && Request::is('item/*'))
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/js/lightbox.min.js" type="text/javascript"></script>
<script>
    lightbox.option({
    	'fadeDuration': 400,
        'resizeDuration': 500,
    	'positionFromTop': 50,
        'wrapAround': true,
      	'showImageNumberLabel': false,
      	'maxWidth': 800,
    });
</script>
@endif

@if(isset($isTop) && $isTop)
<?php
	$slideNum = Ctm::isAgent('sp') ? 3 : 5;
?>

<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
$(document).ready(function() {
	$('.slider-top').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 10000,
          arrows: false,
          fade: false,
          asNavFor: '.slider-nav',
    });
    
    $('.slider-nav').slick({
          slidesToShow: {{ $slideNum }},
          slidesToScroll: 1,
          asNavFor: '.slider-top',
          dots: false,
          centerMode: true,
          focusOnSelect: true,
          prevArrow: '<span class="slick-prev"><i class="fal fa-angle-left"></i></span>',
          nextArrow: '<span class="slick-next"><i class="fal fa-angle-right"></i></span>',
    });
});
</script>
@endif

<script src="{{ asset('js/script.js' . $getNow) }}"></script>

@if(isset(Setting::first()->analytics_code) && Setting::first()->analytics_code != '')
{!! Setting::first()->analytics_code !!}
@endif

</body>
</html>
