<?php 
/* Here is mail view */
?>

<?php //$info = DB::table('siteinfos')['first(); ?>


{{ $user['name'] }} さん
<br><br>
※このメールは配信専用メールのため、ご返信いただけません。
<br><br>
<br>
{{ $actionUser->name }}さんより
{{ $str }}
<br>
{{ $actionUser->name }}さんのプロフィール<br>
{{ url('profile/' . $actionUser->id) }}

<hr>
<br>
<hr>
<br>


<br><br><br><br>
{!! nl2br($set->mail_footer) !!}


