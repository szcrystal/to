@extends('layouts.app')

@section('content')

<?php
use App\User;
use App\Favorite;
use App\UserImg;
?>

<div id="main" class="mp-favorite">

    <div class="panel panel-default">

        <div class="panel-body">

<h3 class="mb-3 card-header">フォローしているユーザー</h3>
@if(! count($follows) > 0)
<p style="min-height: 350px;">フォロー中のユーザーがいません。</p>

@else
<div class="table-responsive table-cart">
    <table class="table table-bordered bg-white"> {{-- table-striped  --}}
    
    @if(! Ctm::isAgent('sp'))
        <thead>
        <tr>
        	<th>登録日</th>
         	<th style="width: 40%;">ユーザー名</th>
          	<th>投稿数</th>
           	<th></th>
			<th></th>
        </tr>
        </thead>
        
        <tbody>
            @foreach($follows as $follow)
            <tr>
            	<?php $targetUser = User::find($follow->target_user_id); ?>
                
                <td><i class="fas fa-heart text-enji"></i> {{ Ctm::changeDate($follow->created_at, 1) }}</td>
                
                <td class="clearfix">
                    
                    {{-- @include('main.shared.smallThumbnail') --}}
                    
                    <div class="w-75 float-left">
                        <a href="{{ url('profile/'. $targetUser->id) }}"> 
                            {{ $targetUser->name }}<br>
                       </a> 
                   </div>
                </td>
                
                <td>
                	<?php
                    	$userImgs = UserImg::where(['user_id'=>$targetUser->id, 'open_status'=>1])->get();
                    ?>
                	
                    {{ $userImgs->count() }}
                </td>
                
                <td>
                    <?php $fours = $userImgs->take(4); ?>
                    
                    @foreach($fours as $four)
                    	<a href="{{ url('post/' . $four->id) }}">
                    	<img src="{{ Storage::url($four->img_path) }}" class="img-fluid w-25">
                        </a>
                    @endforeach
                </td>
                <td>
                    <a href="" class="btn border-secondary bg-white text-small w-100 rounded-0">
                    	商品ページへ <i class="fal fa-angle-double-right"></i>
                    </a>

                 </td>
            </tr>
            @endforeach
        
        </tbody>
 	
    @else
     
        <tbody>
        @foreach($items as $item)
        <tr>
        	<td class="clearfix mb-1">
             <p><i class="fas fa-heart text-enji"></i> 登録日：{{ Ctm::changeDate($item->created_at, 1) }}</P>
             
             <div class="clearfix mb-2">
             	<div class="float-left mr-2">
             		@include('main.shared.smallThumbnail')
             	</div>
                
                <?php 
                    $pots = Item::where(['is_potset'=>1, 'pot_parent_id'=>$item->id])->orderBy('price', 'asc')->get();
                ?>
                
                <div class="float-left w-70">
             		<b>{{ $item->title }}</b>&nbsp;
              		[{{ $item->number }}]
               
               		<p class="mb-1 p-0">
                    	@if($pots->isNotEmpty())
                        	¥{{ Ctm::getItemPrice($pots->first()) }}~
                    	@else
                        	¥{{ Ctm::getItemPrice($item) }}
                    	@endif
                    </p>
               		
                    カテゴリー：<a href="{{ url('category/'. $item->cate_id) }}">{{ $cates->find($item->cate_id)->link_name }}</a>
               </div>
            </div>

            <div class="w-50 float-right mt-2">
                <a href="{{ url('item/'.$item->id) }}" class="btn border-secondary text-small bg-white w-100 rounded-0">
                	商品ページへ <i class="fal fa-angle-double-right"></i>
                </a>
             
             	@if($pots->isEmpty())
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('shop/cart') }}">
                        {{ csrf_field() }}
                                                                               
                        <input type="hidden" name="item_count[]" value="1">
                        <input type="hidden" name="from_item" value="1">
                        <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                        <input type="hidden" name="uri" value="{{ Request::path() }}"> 
                    
                    @if($item->saleDate)
                        <small class="d-block mb-2 mt-2">この商品は{{ Ctm::changeDate($item->saleDate, 1) }}<br>に購入しています</small>
                        <button class="btn btn-custom text-small text-center w-100" type="submit" name="regist_off" value="1">もう一度購入</button>      
                    @else   
                        <button type="submit" class="btn btn-custom text-center text-small w-100 mt-2">カートに入れる</button>
                    @endif 
                    </form> 
                @endif
             </div>
        </tr>
        @endforeach
        
        </tbody>
 		
        
        
	@endif
	</table>
</div>

<div>
	{{ $follows->links() }}
</div>
@endif

<a href="{{ url('mypage') }}" class="btn border-secondary bg-white mt-5">
<i class="fal fa-angle-double-left"></i> マイページに戻る
</a>                  


</div>
</div>
</div>

@endsection


{{--
@section('leftbar')
    @include('main.shared.leftbar')
@endsection


@section('rightbar')
	@include('main.shared.rightbar')
@endsection
--}}


