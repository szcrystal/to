<?php 
/* Here is mail view */
?>

{{$user->name}} さん
<br>
<p>※このメールは配信専用メールのため、ご返信いただけません。</p>
<br>
ご登録ありがとうございます。<br>
登録された内容は下記となります。
<br><br><br>
<hr>
■お客様の情報<br>
【氏名】{{ $user->name }} 様<br>
【メールアドレス】{{ $user->email }}<br>
<br><br>
■詳細のご確認および会員情報の修正はこちら<br>
（メールアドレスの変更やメルマガの配信停止など）<br>
<a href="{{ url('mypage')}}">{{ url('mypage')}}</a>
<br><br>
＊ご利用するにはログインが必要です。<br>
<hr>
<br>


<br><br><br><br>
{!! nl2br($setting->mail_footer) !!}

