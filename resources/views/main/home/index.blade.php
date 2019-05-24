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
			
            <div class="wrap-atcl mb-5">
            	<div class="head-atcl">
                    <h2>人気のタグ</h2>
                </div>
                
            	@foreach($popTags as $poptag)
                	<span class="rank-tag">
                    	<a href="{{ url('tag/' . $poptag->slug) }}">{{ $poptag->name }}</a>
                    </span>
                @endforeach
            </div>
            
            
            <div class="wrap-atcl top-first">
            <div class="head-atcl">
                <h2>人気の投稿</h2>
            </div>
        
            <div class="clearfix">
                @foreach($userImgs as $userImg)

                   <article class="main-atcl">
                                                        
                        <?php $strNum = Ctm::isAgent('sp') ? 16 : 25; ?>
                        @include('main.shared.atcl')
 
                    </article>

                @endforeach
            </div>
            
            <a href="" class="btn btn-block btn-custom bg-white border-secondary rounded-0">もっと見る <i class="fal fa-angle-double-right"></i></a>
            
        </div>
            
            
            

    
    	<a href="{{ url('recommend-info') }}" class="btn btn-block btn-custom bg-white border-secondary rounded-0">もっと見る <i class="fal fa-angle-double-right"></i></a>
    

 


	</div><!-- top cont --> 

</div>
</div>

@endsection


{{--
@section('leftbar')
    @include('main.shared.leftbar')
@endsection
--}}



