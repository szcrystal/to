@extends('layouts.app')

<?php
use App\Setting;
use App\Favorite;
use App\TopSetting;
?>


@section('content')
<div id="main" class="top home">

    <div class="panel panel-default">

        <div class="panel-body top-cont">


@foreach($firstItems as $key => $firstItem)
	
    <?php 
        if(strpos($key, '新着情報') !== false)
            $lineType = 1;
        elseif(strpos($key, 'ランキング') !== false) {
            $lineType = 2; 
            $rankNum = 1;
        }
        elseif(strpos($key, 'チェック') !== false)
            $lineType = 3;
    ?>

    @if(isset($firstItem) && count($firstItem) > 0)

        <div class="wrap-atcl top-first">
            <div class="head-atcl">
                <h2>{{ $key }}</h2>
            </div>
        
            <div class="clearfix">
                @foreach($firstItem as $item)

                   <article class="main-atcl">
                        @if($lineType == 1)
                            <span class="top-new">NEW！</span>
                        @elseif($lineType == 2)
                            <span class="top-rank"><i class="fas fa-crown"></i><em>{{ $rankNum }}</em></span>
                        @endif
                                                        
                        <?php $strNum = Ctm::isAgent('sp') ? 16 : 25; ?>
                        @include('main.shared.atcl')
 
                    </article>
                    
                    @if($lineType == 2)
                        <?php $rankNum++; ?>
                    @endif
                        
              
                    <?php 
                        if($lineType == 1)
                            $slug = 'new-items';
                        elseif($lineType == 2)
                            $slug = 'ranking';
                        elseif($lineType == 3)
                            $slug = 'recent-items';
                    ?>    

                @endforeach
            </div>
            
            <a href="{{ url($slug) }}" class="btn btn-block btn-custom bg-white border-secondary rounded-0">もっと見る <i class="fal fa-angle-double-right"></i></a>
            
        </div>
        
    @endif
@endforeach

@if(isset($allRecoms) && count($allRecoms) > 0)
<div class="wrap-atcl top-second">
	<div class="head-atcl">
        <h2>おすすめ情報</h2>
    </div>
    
	<div class="clearfix">
    	@foreach($allRecoms as $recom)
        	<article class="main-atcl clearfix"> 
            
            <?php
            	$objName = get_class($recom);
                
                if($objName == 'App\Category') {
            		$slugType = 'category';
                }
                elseif($objName == 'App\CategorySecond') {
                	$slugType = 'category/' . $cates->find($recom->parent_id)->slug;
                }
                elseif($objName == 'App\Tag') {
                	$slugType = 'tag';
                }

                
//            	if(strpos($recom->top_img_path, 'category') !== false) {
//            		$slugType = 'category';
//                }
//                elseif(strpos($recom->top_img_path, 'subcate') !== false) {
//                	$slugType = 'category/' . $cates->find($recom->parent_id)->slug;
//                }
//                elseif(strpos($recom->top_img_path, 'tag') !== false) {
//                	$slugType = 'tag';
//                }
            ?>
        
                
                <div class="img-box">
                    <a href="{{ url($slugType . '/'. $recom->slug) }}">
                    <img src="{{ Storage::url($recom->top_img_path) }}" alt="{{ $recom->top_title }}">
                    </a>
                </div>
                
                <div class="meta">
                    <h3><a href="{{ url($slugType . '/'. $recom->slug) }}">{{ $recom->top_title }}</a></h3>
                    
                    <p>{!! nl2br($recom->top_text) !!}</p>    
                </div>
                
            </article>
        @endforeach
        
    </div>
    
    <a href="{{ url('recommend-info') }}" class="btn btn-block btn-custom bg-white border-secondary rounded-0">もっと見る <i class="fal fa-angle-double-right"></i></a>
    
</div>
@endif
 


	</div><!-- top cont --> 

    </div>

</div>

@endsection



@section('leftbar')
    @include('main.shared.leftbar')
@endsection




