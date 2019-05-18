<?php
//use App\Setting;
use App\UserImg;
?>

@if(isset($userImg->img_path) && $userImg->img_path != '')
    <img src="{{ Storage::url($userImg->img_path) }}" alt="" class="img-fluid" width="80">

@else
    <span class="no-img mr-2"><small>No Image</small></span>
@endif


