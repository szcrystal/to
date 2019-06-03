<?php

namespace App\Mail;

use App\Setting;
use App\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Register extends Mailable
{
    use Queueable, SerializesModels;

	public $setting;
    public $userId;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->setting = Setting::get()->first();
        
        $this->userId = $userId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	//$templ = MailTemplate::where(['type_code'=>'register', ])->get()->first();
    	
        $title = 'ユーザー登録が完了しました';
        
        
        $user = User::find($this->userId);
        
        return $this->from('no-reply@tonaniwa.com', $this->setting->admin_name)
                    ->view('emails.register')
                    ->with([
//                        'header' => $templ->header,
//                        'footer' => $templ->footer, 
                        'user' => $user,
//                        'isUser' => 1,        
                    ])
                    ->subject($title);
    }
}
