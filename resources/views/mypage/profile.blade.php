@extends('layouts.app')

@section('content')

<?php

//use

?>


	{{-- @include('main.shared.carousel') --}}

<div id="main" class="top">

        <div class="panel panel-default">

            <div class="panel-body">
                {{-- @include('main.shared.main') --}}

				<div class="main-list clearfix">

<h3 class="mb-3 card-header">{{ $user->name }} さんのプロフィール</h3>

<div class="my-4 clearfix">
<ul class="float-right">
<li><a href="#">このユーザーからのコメントをブロックする</a><br>
<li><a href="#">このユーザーについて報告する</a>
</ul>
</div>

<div class="single-left">
	<h4>最近の投稿</h4>
	<div class="clearfix mb-3">
        @foreach($userImgs as $userImg)
        	<a href="{{ url('post/' . $userImg->id) }}">
            <img src="{{ Storage::url($userImg->img_path) }}" class="img-fluid float-left w-25">
            </a>
        @endforeach
    </div>
    
    <a href="" class="btn btn-block btn-custom bg-white border-secondary rounded-0 text-secondary">すべて見る <i class="fal fa-angle-double-right"></i></a>
</div>


<div class="single-right">
	<div>
    	<div class="py-4">
            <span class="icon-wrap">
                <img src="{{ Storage::url($user->icon_img_path) }}" class="img-fluid">
            </span>
            
            {{ $user->name }}
        </div>
    </div>
    
    <div class="table-responsive table-custom">
        <table class="table table-borderd border">
            
            <tr class="form-group">
                <th>投稿数</th>
                <td>
                    {{ $numArr['post'] }}
                </td>
            </tr>
            
            <tr class="form-group">
                <th>フォロー</th>
                <td>
                   {{ $numArr['follow'] }} 
                </td>
            </tr>
            
            <tr class="form-group">
                <th>フォロワー</th>
                <td>
                  {{ $numArr['follower'] }}  
                </td>
            </tr>
            
            <tr class="form-group">
                <th>タグ</th>
                <td>
                    {{ $numArr['tag'] }}
                </td>
            </tr>
            
            <tr class="form-group">
                <th>いいね（している数か、されている数か）</th>
                <td>
                    {{ $numArr['good'] }}
                </td>
            </tr>
            
            <tr class="form-group">
                <th>お気に入り</th>
                <td>
                    {{ $numArr['favorite'] }}
                </td>
            </tr>
        
        </table>
    </div>
    
    <div class="mb-3">
    	{!! nl2br($user->profile) !!}
    </div>
    
    <div>
    	<div class="table-responsive table-custom">
            <table class="table table-borderd border">
                <tr class="form-group">
                    <th>よく使うタグ</th>
                    <td>
                        
                    </td>
                </tr>
                
                <tr class="form-group">
                    <th></th>
                    <td>
                        
                    </td>
                </tr>
                
                <tr class="form-group">
                    <th></th>
                    <td>
                        
                    </td>
                </tr>
                
                <tr class="form-group">
                    <th></th>
                    <td>
                        
                    </td>
                </tr>
                
                
            </table>
    	</div>
    	
    </div>

</div>


{{--
<form class="form-horizontal" role="form" method="POST" action="{{ $url }}" enctype="multipart/form-data">
    {{ csrf_field() }}
--}}


         



</div>
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


