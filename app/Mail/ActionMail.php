<?php

namespace App\Mail;

use App\Setting;
use App\UserImg;
use App\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $set;
	public $imgId;
    public $actionUserId;
    public $str;
    
    
    public function __construct($imgId, $actionUserId, $str)
    {
        $this->set = Setting::get()->first();
        
        //$this->saleId = $saleId;
        $this->imgId = $imgId;
        //$this->targetId = $targetId
        $this->actionUserId = $actionUserId;
        $this->str = $str;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->imgModel = new UserImg;
        
        $userImg = UserImg::find($this->imgId);
        $user = User::find($userImg->user_id);
        
        $actionUser = User::find($this->actionUserId);
        
//        $this->pmChildModel = new PayMethodChild;
//        $this->itemModel = new Item;
//        $this->dcModel = new DeliveryCompany;
        
        //$templ = MailTemplate::where(['type_code'=>'itemDelivery', ])->get()->first();
        //$templ = MailTemplate::find($this->mailId);
        
        //$thisSale = Sale::find($this->saleId);
        
//        $sales = Sale::find($this->saleIds);
//        $saleRelId = $sales->first()->salerel_id;
//        
//        $saleRel = SaleRelation::find($saleRelId);
//        
//        if($saleRel->is_user) 
//            $user = User::find($saleRel->user_id);
//        else
//            $user = UserNoregist::find($saleRel->user_id);
//        
//
//        $receiver = Receiver::find($saleRel->receiver_id);

        //return $this->from($this->setting->admin_email, $this->setting->admin_name)
        return $this->from('no-reply@tonaniwa.com', $this->set->admin_name)
                    ->view('emails.actionMails')
                    ->with([
                    	//'templ' => $templ, //ここをコメントアウトすればFailedJobの確認ができる。errorメールの送信はProviders/AppServiceProvider内にて
                        //'header' => $templ->header,
                        //'footer' => $templ->footer, 
                        //'thisSale' => $thisSale,
                        'userImg' => $userImg,
                        'user' => $user,
                        'actionUser' => $actionUser,
//                        'receiver' => $receiver,
//                        'isUser' => 1,        
                    ])
                    ->subject($this->str);
    }
}
