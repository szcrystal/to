@extends('layouts.app')

<?php
use App\User;

use App\Setting;
?>


@if(! Ctm::isAgent('sp'))
@section('belt')

@endsection
@endif



@section('content')

    <div id="main" class="single">
    	
        {{--
        @if(! Ctm::isAgent('sp'))
        	@include('main.shared.bread')
        @endif
        --}}
        
        
        
		
        <div class="head-frame clearfix">
            
            <div class="single-left">
            
            	<?php //================================================================= 
                	$imgId = $userImg->id;
                ?>
                
                @if($userImg -> img_path)
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="false" data-interval="false">

                          <div class="carousel-inner">
                            <div class="carousel-item active">
                            	
                                <?php $mainCaption = ''; ?>
                            	
                                @if(isset($userImg->img_path))
                                	<?php $mainCaption = $userImg->explain; ?>
                                    <div class="carousel-caption d-block">
                                        {{ $mainCaption }}
                                    </div>
                                @endif
                                
                                @if(! Ctm::isAgent('sp'))
                                <a href="{{ Storage::url($userImg->img_path) }}" data-lightbox="{{ $userImg->sort_num }}" data-title="{{ $mainCaption }}">
                                @endif
                               
                                    <img class="d-block w-100" src="{{ Storage::url($userImg->img_path) }}" alt="First slide">
                                
                                @if(! Ctm::isAgent('sp'))
                                </a>
                                @endif
                            </div>
                            
                            
                                                     
                          
                          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fal fa-angle-left"></i></span>
                            <span class="sr-only">Previous</span>
                          </a>
                          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"><i class="fal fa-angle-right"></i></span>
                            <span class="sr-only">Next</span>
                          </a>
                          
                          </div>
                          
                          
                    </div>
                    
                @else
                    <span class="no-img">No Image</span>
                @endif
            
            	<?php //END ================================================================= ?>    


				<div class="border border-primary my-3">
                	{{ $userImg->explain }}
                </div>


			</div><!-- left -->
            
            @if(Ctm::isAgent('sp'))
				@include('main.shared.bread')
			@endif
			
            <div class="single-right">
            	
                <?php //================================================================= ?>
                
                	<span class="w-25"><img src="{{ Storage::url($user->icon_img_path) }}" class="w-25 rounded-5"></span>{{ $user->name }}
                
                	<h2 class="single-title">{{ $userImg -> explain }}<br><span></span></h2>
                 	
                    <div class="prof">
                    	<h3>プロフィール</h3>
                    	<p class="text-small">{!! nl2br($user->profile) !!}</p>
               		</div>
                
                
                <div class="favorite my-4" data-type='single'>
                    @if(Auth::check())
                        <?php
                            if($isFav) {
                                $on = ' d-none';
                                $off = ' d-inline'; 
                                $str = 'いいね済み';              
                            }
                            else {
                                $on = ' d-inline';
                                $off = ' d-none';
                                $str = 'いいね';
                            }               
                        ?>

                        <span class="fav fav-on{{ $on }}" data-id="{{ $userImg->id }}" data-type="good"><i class="far fa-heart"></i></span>
                        <span class="fav fav-off{{ $off }}" data-id="{{ $userImg->id }}" data-type="good"><i class="fas fa-heart"></i></span>
                        
                        <small class="fav-str"><span class="loader"><i class="fas fa-square"></i></span>{{ $str }}</small> 
                        
                    @else
                        <span class="fav-temp"><i class="far fa-heart"></i></span>
                        <small class="fav-str"><a href="{{ url('login') }}"><b>ログイン</b></a>するとお気に入りに登録できます</small>   
                    @endif 	   
                </div>
                
                <div class="favorite my-4" data-type='single'>
                    @if(Auth::check())
                        <?php
                            if($isFav) {
                                $on = ' d-none';
                                $off = ' d-inline'; 
                                $str = 'お気に入り済みです';              
                            }
                            else {
                                $on = ' d-inline';
                                $off = ' d-none';
                                $str = 'お気に入りに保存';
                            }               
                        ?>

                        <span class="fav fav-on{{ $on }}" data-id="{{ $userImg->id }}" data-type="favorite"><i class="far fa-folder-plus"></i></span>
                        <span class="fav fav-off{{ $off }}" data-id="{{ $userImg->id }}" data-type="favorite"><i class="fas fa-folder-plus"></i></span>
                        
                        <small class="fav-str"><span class="loader"><i class="fas fa-square"></i></span>{{ $str }}</small> 
                        
                    @else
                        <span class="fav-temp"><i class="far fa-folder-plus"></i></span>
                        <small class="fav-str"><a href="{{ url('login') }}"><b>ログイン</b></a>するとお気に入りに登録できます</small>   
                    @endif 	   
                </div>
                
                

                
                
                    
                <div class="tags mt-4 mb-1">
                    <?php $num = 0; ?>
                    @include('main.shared.tag')
                </div>    
            	
            </div><!-- right -->


			<?php //================================================================= ?> 
                <div class="single-recom">

					   

            	</div><!-- single-recom -->
            <?php //================================================================= ?>
                

        </div><!-- head-frame -->
        

        <div class="recent-check mt-3 pt-1">
            
        </div>


            
		
    </div><!-- id -->
@endsection
