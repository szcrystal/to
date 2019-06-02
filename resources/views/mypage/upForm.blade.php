@extends('layouts.app')

@section('content')

<?php
//use App\Item;
?>


<div id="main" class="history">

        <div class="panel panel-default">

            <div class="panel-body">


<h3 class="mb-3 card-header">画像UP</h3>

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Error!!</strong> 追加できません<br><br>
        <ul>
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

    <div class="col-lg-12 mb-5">
        <form class="form-horizontal" role="form" method="POST" action="/mypage/ups" enctype="multipart/form-data">
        
        	<div class="form-group mb-4">
                <div class="clearfix">
                    <button type="submit" class="btn btn-primary btn-block mx-auto w-btn w-25">更　新</button>
                </div>
                @if(isset($item))
                	<b class="text-big">ID：{{ $item->id }}</b>
                @endif
            </div>

            {{ csrf_field() }}
            
            @if(isset($edit))
                <input type="hidden" name="edit_id" value="{{ $editId }}">
            @endif


			<div class="clearfix mb-3 spare-box">
            <hr class="my-4">
            <?php
                $s=0;
                //use App\Setting;
                //$subSetCount = 10;
                //$setCount = Setting::get()->first()->snap_count;
            ?>
            
            @while($s < $primaryCount)
                <div class="clearfix spare-img thumb-wrap">

                    <fieldset class="w-25 float-right">
                        <div class="checkbox text-right px-0">
                            <label>
                                <?php
                                    $checked = '';
                                    if(Ctm::isOld()) {
                                        if(old('del_spare.'.$s))
                                            $checked = ' checked';
                                    }
                                    else {
                                        if(isset($item) && $item->del_spare) {
                                            $checked = ' checked';
                                        }
                                    }
                                ?>

                                <input type="hidden" name="del_spare[{{$s}}]" value="0">
                                <input type="checkbox" name="del_spare[{{$s}}]" value="1"{{ $checked }}> この画像を削除
                            </label>
                        </div>
                    </fieldset>
                    
                    <fieldset class="float-left w-75 clearfix">
                        <div class="w-25 float-left thumb-prev">
                            @if(count(old()) > 0)
                                @if(old('spare_thumb.'.$s) != '' && old('spare_thumb.'.$s))
                                	<img src="{{ Storage::url(old('spare_thumb.'.$s)) }}" class="img-fluid">
                                
                                @elseif(isset($spares[$s]) && $spares[$s]->img_path)
                                	<img src="{{ Storage::url($spares[$s]->img_path) }}" class="img-fluid">
                                
                                @else
                                	<span class="no-img">No Image</span>
                                @endif
                            @elseif(isset($spares[$s]) && $spares[$s]->img_path)
                                <img src="{{ Storage::url($spares[$s]->img_path) }}" class="img-fluid">
                            
                            @else
                                <span class="no-img">No Image</span>
                            @endif
                        </div>

                        <div class="col-md-8 pull-left text-left form-group{{ $errors->has('spare_thumb.'.$s) ? ' is-invalid' : '' }}">
                            <label for="model_thumb" class="col-md-12 text-left">画像 <span class="text-primary">{{ $s+1 }}</span></label>
                            <div class="w-100">
                                <input id="model_thumb" class="thumb-file" type="file" name="spare_thumb[]">

                                @if ($errors->has('spare_thumb.'.$s))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('spare_thumb.'.$s) }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>
                    </fieldset>
                </div>
                
                
                <fieldset>
                	<select class="form-control col-md-6 mt-2{{ $errors->has('cate_id') ? ' is-invalid' : '' }}" name="cate_id">
                        
						<option value="0">選択して下さい</option>
                        
                    	@foreach($allCates as $cate)
                        	<?php
								$selected = '';
                                
                                if(Ctm::isOld()) {
                                    if(old('cate_id') == $cate->id) $selected = 'selected';
                                }
                                else {
                                    if(isset($userImg)) {
                                    	if($userImg->cate_id == $cate->id) $selected = 'selected';
                                    }
                                }
                            ?>
                            
                    		<option value="{{ $cate->id }}" {{ $selected }}>{{ $cate->name }}</option>
                    	@endforeach
                    
                    </select>
                	
                    @if ($errors->has('cate_id'))
                        <div class="text-danger">
                            <span class="fa fa-exclamation form-control-feedback"></span>
                            <span>{{ $errors->first('cate_id') }}</span>
                        </div>
                    @endif
                </fieldset>
                
                
                <fieldset>
                    <textarea class="form-control mt-2{{ $errors->has('explain') ? ' is-invalid' : '' }}" name="explain">{{ Ctm::isOld() ? old('explain') : (isset($userImg) ? $userImg->explain : '') }}</textarea>

                    @if ($errors->has('explain'))
                        <div class="text-danger">
                            <span class="fa fa-exclamation form-control-feedback"></span>
                            <span>{{ $errors->first('explain') }}</span>
                        </div>
                    @endif
                </fieldset>
                
                
                
                <fieldset class="mb-5 form-group">
                    <label for="pot_count" class="control-label">URL</label>
                    <input class="form-control col-md-6{{ $errors->has('target_url') ? ' is-invalid' : '' }}" name="target_url" value="{{ Ctm::isOld() ? old('target_url') : (isset($userImg) ? $userImg->target_url : '') }}">
                    

                    @if ($errors->has('target_url'))
                        <div class="text-danger">
                            <span class="fa fa-exclamation form-control-feedback"></span>
                            <span>{{ $errors->first('target_url') }}</span>
                        </div>
                    @endif
                </fieldset>

                
                
                <div class="clearfix tag-wrap">
                <div class="tag-group form-group{{ $errors->has('tag-group') ? ' is-invalid' : '' }}">
                    <label for="tag-group" class="control-label">タグ</label>
                    <div class="clearfix">
                        <input id="tag-group" type="text" class="form-control col-md-5 tag-control" name="input-tag-group" value="" autocomplete="off" placeholder="Enter tag">

                        <div class="add-btn" tabindex="0">追加</div>

                        <span style="display:none;">{{ implode(',', $allTags) }}</span>

                        <div class="tag-area">
                            @if(count(old()) > 0)
                                <?php
                                    //$tagNames = old($tag->slug);
                                    $tagNames = old('tags');
                                ?>
                            @endif

                            @if(isset($tagNames))
                                @foreach($tagNames as $name)
                                <span><em>{{ $name }}</em><i class="fa fa-times del-tag" aria-hidden="true"></i></span>
                                <input type="hidden" name="tags[]" value="{{ $name }}">
                                @endforeach
                            @endif

                        </div>

                    </div>

                </div>

            	</div><?php //tagwrap ?>
                
                <div>
                    <fieldset class="w-100">
                        <div class="checkbox text-left px-0">
                            <label>
                                <?php
                                    $checked = '';
                                    if(Ctm::isOld()) {
                                        if(! old('open_status'))
                                            $checked = ' checked';
                                    }
                                    else {
                                        if(isset($userImg) && ! $userImg->open_status) {
                                            $checked = ' checked';
                                        }
                                    }
                                ?>

                                <input type="hidden" name="open_status" value="1">
                                <input type="checkbox" name="open_status" value="0"{{ $checked }}> この投稿をまだ公開しない
                            </label>
                        </div>
                    </fieldset>
                </div>
                
                <hr class="my-4">
                
                
                <div>
                	<label>アイテムタグをつける</label>
                </div>
                
                

                <input type="hidden" name="spare_count[]" value="{{ $s }}">

                <?php $s++; ?>
            @endwhile

        </div>
	
    </form>
</div>



<a href="{{ url('mypage/ups') }}" class="btn border border-secondary bg-white mt-5">
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


