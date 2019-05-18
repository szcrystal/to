<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    @if(Ctm::isEnv('alpha'))
    <meta name="robots" content="noindex, nofollow">
    @endif
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if(isset($metaTitle)){{ $metaTitle }} | @endif{{ config('app.name', 'グリーンロケット') }}</title>
    
    @if(isset($metaDesc))
    <meta name="description" content="{{ str_replace(PHP_EOL, '', $metaDesc) }}">
    @endif
    
    @if(isset($metaKeyword))
    <meta name="keywords" content="{{ $metaKeyword }}">
    @endif
    
    <!-- Styles -->    
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-9ralMzdK1QYsk4yBY680hmsb4/hJ98xK3w0TIaJ3ll4POWpWUYaA2bRjGGujGT8w" crossorigin="anonymous">

    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    
    @if(! Ctm::isAgent('sp') && Request::is('item/*'))
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/css/lightbox.css" rel="stylesheet">
    @endif

	@if(isset($isTop) && $isTop)    
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
	@endif
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    
    <?php $getNow = '?up=' . time(); ?>
    
    <link href="{{ asset('css/style.css'. $getNow) }}" rel="stylesheet">
    
    @if(Ctm::isAgent('all'))
    <link href="{{ asset('css/style-sp.css' . $getNow) }}" rel="stylesheet">
	@endif

	{{-- <script type="text/javascript" src="//code.jquery.com/jquery-2.1.0.min.js"></script> --}}


    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>

    </script>

</head>


<?php $switch = 0; ?>
@if(Ctm::isEnv('local') && $switch)
<div style="position: relative; bottom:0; z-index:10000; background:red; width: 100%;">
<?php 
print_r(session('item.data')); 
?>
</div>
@endif


