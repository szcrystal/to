@extends('layouts.app')

<?php
// use App\Setting;
?>

@section('belt')
<div class="tophead-wrap">

    
    @if(isset($isTop) && $isTop)
        @include('main.shared.carousel')
    @endif
</div>
@endsection

{{--
@section('bread')
@include('main.shared.bread')
@endsection
--}}

@section('content')

<?php
    use App\Tag;
    use App\TagRelation;
    use App\Setting;
?>

<div id="main" class="archive">

<div class="panel panel-default top-cont">
	
    <?php $orgObj = null; ?>
    
    <div class="panel-heading">
        <h2 class="mb-3 card-header">
        @if($type == 'category')
            {{ $cate->name }}
            <?php $orgObj = $cate; ?>
            
        @elseif($type == 'subcategory')
            <small class="d-block pb-2">{{ $cate->name }}</small>{{ $subcate->name }}
            <?php $orgObj = $subcate; ?>
            
        @elseif($type == 'tag')
            タグ：{{ $tag->name }}
            <?php $objs = $userImgs; ?>
            
        @elseif($type=='search')
            検索ワード：
            @if($searchStr == '')
                未入力です
            @else
                @if(!count($userImgs))
                {{ $searchStr }}の商品がありません
                @else
                {{ $searchStr }}
                @endif
            @endif
            
            <?php $objs = $userImgs; ?>
            
        @elseif($type == 'unique')
            {{ $title }}
        @endif
        </h2>
    </div>
        
    <div class="panel-body">
        
        
        <div class="pagination-wrap">
            {{ $userImgs->links() }}
        </div>
        
        <?php 
            $n = Ctm::isAgent('sp') ? 3 : 4;
            $itemArr = array_chunk($objs->all(), $n); 
        ?>
        
        @foreach($itemArr as $itemVal)
            <div class="clearfix">

            @foreach($itemVal as $userImg)
                <article class="main-atcl">
                        
                    <?php $strNum = Ctm::isAgent('sp') ? 16 : 25; ?>
                    @include('main.shared.atcl')
                        
                </article>
            @endforeach
            
            </div>
        @endforeach
    
    </div>
        
    <div class="pagination-wrap">
        {{ $userImgs->links() }}
    </div>
            
</div>
</div>

@endsection


{{--
@section('leftbar')
    @include('main.shared.leftbar')
@endsection
--}}

