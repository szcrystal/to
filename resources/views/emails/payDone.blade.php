<?php 
/* Here is mail view */
?>

<?php //$info = DB::table('siteinfos')['first(); ?>


{{ $user['name'] }} 様
@if($isUser)
<br><br>
※このメールは配信専用メールのため、ご返信いただけません。
<br><br>
{!! nl2br( $header ) !!}
@else
<br>よりご注文がありました。<br>
ご注文内容は下記となります。
@endif
<br>
<br>
@if(isset($saleRel->information) && $saleRel->information != '')
【グリーンロケットからのお知らせ】<br>
{!! nl2br($saleRel->information) !!}<br><br>
@endif

<hr>
【ご注文番号】：{{ $saleRel->order_number }}<br>
【ご注文日】：{{ Ctm::changeDate($saleRel->created_at, 1) }}<br>
{{--【お支払い方法】：{{ $payName }}<br> --}}
【ご注文者】：{{ $user->name }} 様<br>
{{--
【お届け先】： 
<div style="margin: 0 0 1.5em 1.0em;">
{{ $receiver->name }} 様<br>
〒{{ Ctm::getPostNum($receiver->post_num) }}<br>
{{ $receiver->prefecture }}{{ $receiver->address_1 }}{{ $receiver->address_2 }}<br>
{{ $receiver->address_3 }}
</div>
【発送日】： {{ date('Y/m/d', time()) }}<br>

@if(isset($thisSale->plan_date))
【お届け予定日時】： {{ $thisSale->plan_date }}<br>
@endif
--}}
【ご注文商品】： <br>
<?php $num = 1; 
?>
@foreach($sales as $sale)
<div style="margin: 0 0 1.5em 1.0em;">
<div>{{ $num }}.</div>
商品番号: {{ $itemModel->find($sale->item_id)->number }}<br>
商品名: {{ Ctm::getItemTitle($itemModel->find($sale->item_id)) }}<br>
数量: {{ $sale->item_count}}<br>
金額：¥{{ number_format($sale->total_price) }}（税込）
</div>

<?php $num++; ?>
@endforeach

@if(isset($saleRel->user_comment) && $saleRel->user_comment != '')
【コメント】：
<div style="margin: 0 0 1.0em 1.0em;">
{!! nl2br($saleRel->user_comment) !!}
</div>
@endif

【お買上金額】：
<div style="margin: 0 0 1.0em 1.0em;">
商品金額合計：￥{{ number_format($saleRel->all_price) }} <br>
送料：￥{{ number_format($saleRel->deli_fee) }} <br>
@if($saleRel->pay_method == 2)
コンビニ決済手数料：￥{{ number_format($saleRel->cod_fee) }}<br>
@elseif($saleRel->pay_method == 4)
GMO後払い手数料：￥{{ number_format($saleRel->cod_fee) }}<br>
@elseif($saleRel->pay_method == 5)
代引手数料：￥{{ number_format($saleRel->cod_fee) }} <br>
@endif

@if(Auth::check())
ポイント利用：{{ $saleRel->use_point }}ポイント <br>
@endif
<?php
$allTotal = $saleRel->all_price + $saleRel->deli_fee - $saleRel->use_point;
?>
<b style="display:block; font-size:1.1em; margin-top:0.5em;">ご注文金額合計：￥{{ number_format($allTotal) }} （税込）</b>
</div>
【お支払方法】：{{ $pmModel->find($saleRel->pay_method)->name }} <br><br>
【ご入金確認日】：{{ Ctm::changeDate($saleRel->pay_date, 1) }} <br>
<br>
<hr>
<br>
@if($isUser)
    @if(isset($saleRel->information_foot) && $saleRel->information_foot != '')
    	{!! nl2br($saleRel->information_foot) !!}<br><br><br>
    @endif

	{!! nl2br( $footer ) !!}
@endif

<br><br><br><br>
{!! nl2br($setting->mail_footer) !!}


