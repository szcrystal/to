@extends('layouts.app')

@section('content')


	{{-- @include('main.shared.carousel') --}}

<div id="main" class="top">

        <div class="panel panel-default">

            <div class="panel-body">
                {{-- @include('main.shared.main') --}}

				<div class="main-list clearfix">
<h3 class="mb-3 card-header">会員登録情報</h3>

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <i class="far fa-exclamation-triangle"></i> 確認して下さい。
        <ul class="mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<div class="">
<?php
$url = $isMypage ? url('mypage/register') : url('register');
?>

<form class="form-horizontal" role="form" method="POST" action="{{ $url }}" enctype="multipart/form-data">

    {{ csrf_field() }}

<div class="table-responsive table-custom">
	
    <div class="form-group clearfix mb-4 thumb-wrap">
        <fieldset class="w-25 float-right">
            <div class="col-md-12 checkbox text-right px-0">
                <label>
                    <?php
                        $checked = '';
                        if(Ctm::isOld()) {
                            if(old('del_mainimg'))
                                $checked = ' checked';
                        }
                        else {
                            if(isset($item) && $item->del_mainimg) {
                                $checked = ' checked';
                            }
                        }
                    ?>

                    <input type="hidden" name="del_mainimg" value="0">
                    <input type="checkbox" name="del_mainimg" value="1"{{ $checked }}> この画像を削除
                </label>
            </div>
        </fieldset>
        
        <fieldset>
            <div class="float-left col-md-4 px-0 thumb-prev">
                @if(count(old()) > 0)
                    @if(old('icon_img_path') != '' && old('icon_img_path'))
                    	<img src="{{ Storage::url(old('icon_img_path')) }}" class="img-fluid">
                    @elseif(isset($user) && $item->icon_img_path)
                    	<img src="{{ Storage::url($item->icon_img_path) }}" class="img-fluid">
                    @else
                    	<span class="no-img">No Image</span>
                    @endif
                @elseif(isset($user) && $user->icon_img_path)
                	<img src="{{ Storage::url($user->icon_img_path) }}" class="img-fluid">
                @else
                	<span class="no-img">No Image</span>
                @endif
            </div>
            

            <div class="float-left col-md-8 pl-3 pr-0">
                <fieldset class="form-group{{ $errors->has('icon_img_path') ? ' is-invalid' : '' }}">
                    <label for="icon_img">ユーザー画像</label>
                    <input class="form-control-file thumb-file" id="icon_img" type="file" name="icon_img_path">
                </fieldset>
            
                @if ($errors->has('icon_img_path'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('icon_img_path') }}</strong>
                    </span>
                @endif
                            
            </div>
        </fieldset>


        @if ($errors->has('icon_img'))
            <div class="text-danger">
                <span class="fa fa-exclamation form-control-feedback"></span>
                <span>{{ $errors->first('icon_img') }}</span>
            </div>
        @endif
    </div>
    
    <table class="table table-borderd border">
        
        <tr class="form-group">
             <th>ユーザー名<em>必須</em></th>
               <td>
                <input class="form-control rounded-0 col-md-12{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ Ctm::isOld() ? old('name') : (isset($user) ? $user->name : '') }}" placeholder="例）山田太郎">
               
                @if ($errors->has('name'))
                    <div class="text-danger">
                        <span class="fa fa-exclamation form-control-feedback"></span>
                        <span>{{ $errors->first('name') }}</span>
                    </div>
                @endif
            </td>
         </tr> 
      
                   
         <tr class="form-group">
             <th>メールアドレス<em>必須</em></th>
               <td>
                <input type="email" class="form-control rounded-0 col-md-12{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ Ctm::isOld() ? old('email') : (isset($user) ? $user->email : '') }}" placeholder="例）abcde@example.com">
                
                @if ($errors->has('email'))
                    <div class="help-block text-danger">
                        <span class="fa fa-exclamation form-control-feedback"></span>
                        <span>{{ $errors->first('email') }}</span>
                    </div>
                @endif
            </td>
         </tr>
         
         
        <tr class="form-group">
        <th>プロフィール</th>
            <td>
                 <fieldset>
                    <textarea class="form-control mt-2{{ $errors->has('profile') ? ' is-invalid' : '' }}" name="profile">{{ Ctm::isOld() ? old('profile') : (isset($user) ? $user->profile : '') }}</textarea>

                    @if ($errors->has('profile'))
                        <div class="text-danger">
                            <span class="fa fa-exclamation form-control-feedback"></span>
                            <span>{{ $errors->first('profile') }}</span>
                        </div>
                    @endif
                </fieldset>
            </td>
        </tr>

         
         
         </table>
         </div>
         

    
<div class="table-responsive table-custom">
	@if(! $isMypage)
		<p class="mt-4 text-small">8文字以上（半角）で、忘れないものを入力して下さい。<br>メールアドレスとパスワードは当店をご利用の際に必要となります。</p>
	@endif
    
         
    @if(! $isMypage)
    	<table class="table table-borderd border">
             <tr class="form-group">
                 <th>パスワード<em>必須</em></th>
                   <td>
                    <input type="password" class="form-control rounded-0 col-md-12{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ Ctm::isOld() ? old('password') : (Session::has('all.data.user') ? session('all.data.user.password') : '') }}" placeholder="8文字以上">
                                        
                    @if ($errors->has('password'))
                        <div class="help-block text-danger">
                            <span class="fa fa-exclamation form-control-feedback"></span>
                            <span>{{ $errors->first('password') }}</span>
                        </div>
                    @endif
                </td>
             </tr>
             
             <tr class="form-group">
                <th>パスワードの確認<em>必須</em></th>
                <td>
                    <input type="password" class="form-control rounded-0 col-md-12{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" value="{{ Ctm::isOld() ? old('password_confirmation') : (Session::has('all.data.user') ? session('all.data.user.password_confirmation') : '') }}">
                    
                    @if ($errors->has('password_confirmation'))
                        <div class="help-block text-danger">
                            <span class="fa fa-exclamation form-control-feedback"></span>
                            <span>{{ $errors->first('password_confirmation') }}</span>
                        </div>
                    @endif
                </td>
             </tr>
             
    @else
        <table class="table table-borderd border mt-5">
            <tr class="form-group"> 
                <th>パスワードの変更</th>
                <td>
                    パスワードの変更は <a href="{{ url('password/reset') }}">こちら <i class="fal fa-angle-double-right"></i></a>
                </td>
            </tr>
             
    @endif
    
    </table>
 </div>            
 
    
@if($isMypage)
    <div class="table-responsive table-custom mt-5">
        <table class="table table-borderd border">
             <tr class="form-group">
                
             </tr>
        </table>
     </div>
@endif
         
         
    <div class="mt-4 mb-4">
        <button class="btn btn-block btn-custom col-md-4 mx-auto py-2" type="submit" name="recognize" value="1">登録する</button>
    </div>                
</form>

@if($isMypage)
<a href="{{ url('mypage') }}" class="btn border-secondary bg-white my-3">
<i class="fal fa-angle-double-left"></i> マイページに戻る
</a>
@endif

</div>
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


