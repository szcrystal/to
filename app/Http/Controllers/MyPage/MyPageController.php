<?php

namespace App\Http\Controllers\MyPage;

use App\User;
use App\UserImg;
use App\UserFollow;
use App\Favorite;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\Mail\Register;
use Mail;
use Auth;
use Ctm;
use DateTime;

use Illuminate\Validation\Rule;

class MyPageController extends Controller
{
    public function __construct(User $user, UserImg $userImg, UserFollow $follow, Favorite $favorite)
    {
        
        $this -> middleware('auth', ['except'=>['getRegister', 'postRegister', 'registerEnd']]);
        //$this -> middleware('log', ['only' => ['getIndex']]);
        
        
        $this->user = $user;
        $this->userImg = $userImg;
        $this->follow = $follow;
        $this->favorite = $favorite;
//        $this->userNor = $userNor;
//        $this->sale = $sale;
//        $this->saleRel = $saleRel;
//        $this->payMethod = $payMethod;
//        $this->pref = $pref;
//        $this->favorite = $favorite;
//        $this->category = $category;
//    	$this-> receiver = $receiver;
//        
//        $this->gmoId = Ctm::gmoId();
        
        $this->whereArr = ['open_status'=>1];
        
        $this->perPage = 20;
        
    }
    
    
    
    public function index()
    {
        
//        $itemObjs = Item::orderBy('id', 'desc')->paginate($this->perPage);
//        
//        $cates= $this->category;
        
        $uId = Auth::id();
        $user = $this->user->find($uId);
        
        //$userImgs = $this->userImg->where(['user_id'=>$uId])->get();
        //$status = $this->articlePost->where(['base_id'=>15])->first()->open_date;
        
        return view('mypage.index', ['user'=>$user/*, 'userImgs'=>$userImgs*/]);
    }
    
    public function history()
    {
    	$uId = Auth::id();
        $user = $this->user->find($uId);
        
        $relIds = $this->saleRel->where(['user_id'=>$uId, 'is_user'=>1])->get()->map(function($obj){
        	return $obj->id;
        })->all();
        
//        print_r($relIds);
//        exit;
        
        $sales = $this->sale->whereIn('salerel_id', $relIds)->orderBy('id', 'desc')->paginate($this->perPage);
        
        $saleRel = $this->saleRel;
        //$item = $this->item;
        $pm = $this->payMethod;
        
     	return view('mypage.history', ['user'=>$user, 'saleRel'=>$saleRel, 'sales'=>$sales, 'pm'=>$pm]);   
    }
    
    public function showHistory($saleId)
    {
        $uId = Auth::id();
        $user = $this->user->find($uId);
        
        $sale = $this->sale->find($saleId);
        
        $item = $this->item->find($sale->item_id);
        
        $saleRel = $this->saleRel->find($sale->salerel_id);
        $receiver = $this->receiver->find($saleRel->receiver_id);
        
        $relIds = $this->saleRel->where(['user_id'=>$uId, 'is_user'=>1])->get()->map(function($obj){
            return $obj->id;
        })->all();
        
//        print_r($relIds);
//        exit;
        
        $sales = $this->sale->where(['salerel_id'=>$relIds])->get();
        
        $saleRel = $this->saleRel;
        
        $pm = $this->payMethod;
        
         return view('mypage.historySingle', ['user'=>$user, 'sale'=>$sale, 'saleRel'=>$saleRel, 'receiver'=>$receiver, 'item'=>$item, 'pm'=>$pm]);   
    }
    
    
    public function getRegister(Request $request)
    {
        //$prefs = $this->pref->all();
        
        $user = null;
        
        if(! Auth::check()) {
            //abort(404);
            $isMypage = 0;
        }
        else {   
            $uId = Auth::id();
            $user = $this->user->find($uId);
            $isMypage = 1;
           
                
        }      
       
//        if(Auth::check()) {
//            return redirect('mypage/register');
//        }
//        else {
//            $user = null;
//            $isMypage = 0;
//        
//       	}

       
       
    	return view('mypage.form', ['user'=>$user, 'isMypage'=>$isMypage,]);
    }
    
    public function registerConfirm(Request $request)
    {
    	
     
        
    }
    
    public function postRegister(Request $request)
    {
    	$isMypage = $request->is('mypage/*') ? 1 : 0;
        
        //email用validationの配列
        $mailValid = ['filled', 'email', 'max:255'];
        
        if($isMypage) {
        	$uId = Auth::id(); //マイページの時は自分のidを除外するので 
            $mailValid[] = Rule::unique('users', 'email')->ignore($uId, 'id')->where(function ($query) {
                return $query->where('active', 1); //uniqueする対象をactive:1のみにする
            });
        } 
        else {
        	$mailValid[] = Rule::unique('users', 'email')->where(function ($query) {
                return $query->where('active', 1); //uniqueする対象をactive:1のみにする
            });
        }
        
        $rules = [
            'name' => 'required|max:255',
            //'user.hurigana' => 'required|max:255',
            'email' => $mailValid,
            //'user.tel_num' => 'required|numeric',
//            'cate_id' => 'required',
//            'user.post_num' => 'required|nullable|numeric|digits:7', //numeric|max:7
//            'user.prefecture' => 'required',         
//            'user.address_1' => 'required|max:255',
//            'user.address_2' => 'required|max:255',  
            'password' => 'sometimes|required|min:8|confirmed', 
            'password_confirmation' => 'sometimes|required',                      
  
        ];
        
//        $editModes = $request->has('user.edit_mode.*') ? $request->input('user.edit_mode.*') : array();
//        
//        if(count($editModes)) {
//            foreach($editModes as $key => $val) {
//                if($val == 1) {
//                    $now = new DateTime();
//                    $ym = $now->format('ym');
//                    
//                    $expire = $request->input('user.expire_year.'.$key) . $request->input('user.expire_month.'.$key);
//
//                    $rules['user.expire.'.$key] = [
//                        function($attribute, $value, $fail) use($ym, $expire) {
//                            if ($expire < $ym) 
//                                return $fail('「有効期限」は現在以降を指定して下さい。');
//                        },
//                    ];
//                }
//            }
//        }
        //exit;
        

        //if(! $isMypage) {
        //    $rules['user.password'] = 'required|min:8|confirmed';
       //}
        
        //if(! Auth::check()) {
            //$rules['user.prefecture'] = 'required';
             
        
               
        //}
        
         $messages = [
//            'title.required' => '「商品名」を入力して下さい。',
//            'cate_id.required' => '「カテゴリー」を選択して下さい。',
//            'destination.required_without' => '「配送先」を入力して下さい。', //登録先住所に配送の場合は「登録先住所に配送する」にチェックをして下さい。
//            'pay_method.required' => '「お支払い方法」を選択して下さい。',
//            'use_point.max' => '「ポイント」が保持ポイントを超えています。',
            //'post_thumb.filenaming' => '「サムネイル-ファイル名」は半角英数字、及びハイフンとアンダースコアのみにして下さい。',
            //'post_movie.filenaming' => '「動画-ファイル名」は半角英数字、及びハイフンとアンダースコアのみにして下さい。',
            //'slug.unique' => '「スラッグ」が既に存在します。',
        ];
        
        if(! Ctm::isEnv('local')) {
        	$this->validate($request, $rules, $messages);
        }
        $data = $request->all();
        
        //$data['user']['magazine'] = isset($data['user']['magazine']) ? 1 : 0;
        
//        $data['user']['birth_year'] = $data['user']['birth_year'] ? $data['user']['birth_year'] : null;
//        $data['user']['birth_month'] = $data['user']['birth_month'] ? $data['user']['birth_month'] : null;
//        $data['user']['birth_day'] = $data['user']['birth_day'] ? $data['user']['birth_day'] : null;
        
//        $request->flash();
//        session()->put('registUser', $data['user']);
//        
//        $data = $data['user'];
        
        $data['active'] = 1;
        //exit;
        
        if($isMypage) {
     		$uId = Auth::id();
            $user = $this->user->find($uId);
        }
        else {
           	$data['password'] = bcrypt($data['password']);
            $user = $this->user; 
        }
        
        $user->fill($data);
        $user->save();
        
        //Main-img
        if(isset($data['del_mainimg']) && $data['del_mainimg']) { //削除チェックの時
            if($item->main_img !== null && $item->main_img != '') {
                Storage::delete($item->main_img); //Storageはpublicフォルダのあるところをルートとしてみる
                $item->icon_img_path = null;
                $item->save();
            }
        }
        else {
            if(isset($data['icon_img_path'])) {
                
                //$filename = $request->file('main_img')->getClientOriginalName();
                $filename = $data['icon_img_path']->getClientOriginalName();
                $filename = str_replace(' ', '_', $filename);
                
                //$aId = $editId ? $editId : $rand;
                //$pre = time() . '-';
                $filename = 'user/' . $user->id . '/icon/'/* . $pre*/ . $filename;
                //if (App::environment('local'))
                $path = $data['icon_img_path']->storeAs('public', $filename);
                //else
                //$path = Storage::disk('s3')->putFileAs($filename, $request->file('thumbnail'), 'public');
                //$path = $request->file('thumbnail')->storeAs('', $filename, 's3');
                
                $user->icon_img_path = $path;
                $user->save();
            }
        }
        
        
        if($isMypage) {
        	//return view('mypage.formConfirm', ['data'=>$data, 'isMypage'=>$isMypage]);
            $status = 'ユーザー情報が変更されました。';
            return view('mypage.formEnd', ['data'=>$data, 'isMypage'=>$isMypage, 'status'=>$status]);
        }
        else {
        
        	Mail::to($user->email, $user->name)->queue(new Register($user->id));
        	Auth::login($user);
        
        	$status = 'ユーザー情報が登録されました。';
        	return view('mypage.formEnd', ['data'=>$data, 'isMypage'=>$isMypage, 'status'=>$status]);
        }
    }
    
    //会員登録 新規／変更併用
    public function registerEnd(Request $request)
    {

    	$isMypage = $request->is('mypage/*') ? 1 : 0;
     
         //print_r(session('registUser'));
        //exit;      
     	        
     	if($request->session()->has('registUser')) {
      		$data = session('registUser');
      	}
       	else {
        	return redirect('/');
        }            
        
        
    	if($isMypage) {
     		$uId = Auth::id();
            $user = $this->user->find($uId);
        }
        else {
           	$data['password'] = bcrypt($data['password']);
            $user = $this->user; 
        }
        
        //Birth Input 年月日1つでも0があるなら入力しない　ことにしているがどうか
        if( ! $data['birth_year'] || ! $data['birth_month'] || ! $data['birth_day']) {
            $data['birth_year'] = 0;
            $data['birth_month'] = 0;
            $data['birth_day'] = 0;
        }
        
        
        //カード更新／削除 =======================
        $editCardDatas = array();
        $editCardErrors = null;
        
        $delCardDatas = array();
        $delCardErrors = null;
        
        if($isMypage && isset($data['edit_mode']) && count($data['edit_mode'])) {
        
        	foreach($data['edit_mode'] as $key => $val) {
            
            	if($val == 1) { //カード期限更新
                	$eCardDatas = [
                        'SiteID' => $this->gmoId['siteId'],
                        'SitePass' => $this->gmoId['sitePass'],
                        'MemberID' => $user->member_id,
                        //'MemberID' => 11111,
                        'SeqMode' => 1, //削除時はまとめて削除が出来ないので、物理モードで。毎回論理値を返すとSeqNumberがずれて削除がおかしくなる。
                        'CardSeq' => $key,
                        'Expire' => $data['expire_year'][$key] . $data['expire_month'][$key],
                        'UpdateType' => 2, //ここを2（カード番号以外を更新。カード番号は登録済みの値を引継ぐ）に指定しないと、トークンかカード番号が必要となる
                    ];
                    
                    $eResponse = Ctm::cUrlFunc("SaveCard.idPass", $eCardDatas);
                    
                    $cardArr = explode('&', $eResponse);
                    
                    foreach($cardArr as $res) {
                        $arr = explode('=', $res);
                        $editCardDatas[$arr[0]][$key] = explode('|', $arr[1]);
                    }
                    
                    
                    //$userRegResponse Error処理をここに ***********
                    if(array_key_exists('ErrCode', $editCardDatas)) {
                        $editCardErrors .= "<br>";
                        $editCardErrors .= '[5301-Seq:'. $key .'-'; //cardSeqナンバーをエラーに付ける
                        $editCardErrors .= implode('|', $editCardDatas['ErrInfo'][$key]);
                        //$editCardErrors .= '42G830000';
                        $editCardErrors .= ']';
                    }
                    
                }
            	elseif($val == 2) { //カード削除
                	$dCardDatas = [
                        'SiteID' => $this->gmoId['siteId'],
                        'SitePass' => $this->gmoId['sitePass'],
                        'MemberID' => $user->member_id,
                        //'MemberID' => 11111,
                        'SeqMode' => 1, //削除時はまとめて削除が出来ないので、物理モードで。毎回論理値を返すと削除がおかしくなる。
                        'CardSeq' => $key,
                    ];
                    
                    $dCardResponse = Ctm::cUrlFunc("DeleteCard.idPass", $dCardDatas);
                    
                    //正常：CardSeq=0|1|2|3|4&DefaultFlag=0|0|0|0|0&CardName=||||&CardNo=*************111|*************111|*************111|*************111|*************111&Expire=1905|1904|1908|1907|1910&HolderName=||||&DeleteFlag=0|0|0|0|0
                    $cardArr = explode('&', $dCardResponse);
                    
                    foreach($cardArr as $res) {
                        $arr = explode('=', $res);
                        $delCardDatas[$arr[0]][$key] = explode('|', $arr[1]);
                    }
                    
                    
                    //$userRegResponse Error処理をここに ***********
                    if(array_key_exists('ErrCode', $delCardDatas)) {
                        $delCardErrors .= "<br>";
                        $delCardErrors .= '[5401-Seq:'. $key .'-'; //cardSeqナンバーをエラーに付ける
                        $delCardErrors .= implode('|', $delCardDatas['ErrInfo'][$key]);
                        $delCardErrors .= ']';
                    }
                    else {
                        $user->decrement('card_regist_count'); //DBでカウントを減らす
                    }
                }
            
            }
        }
        
        
        $user->fill($data);
        $user->save();
        //$user->update($data['user']);
        
        if(! Ctm::isLocal()) {
        	session()->forget('registUser');
        }
        
        if($isMypage) {
        	$status = "会員登録情報が変更されました。";
        }
        else {
            $status = "会員情報が登録されました。";
            
        	Mail::to($user->email, $user->name)->queue(new Register($user->id));
        	Auth::login($user);
        }
        
        return view('mypage.formEnd', ['isMypage'=>$isMypage, 'status'=>$status, 'delCardErrors'=>$delCardErrors, 'editCardErrors'=>$editCardErrors, ]);
   
    }
    
    
    //User退会
    public function getOptout()
    {
    	$user = $this->user->find(Auth::id());
        
     	return view('mypage.optout', ['user'=>$user, ]);   
    }
    
    public function postOptout(Request $request)
    {
    	$userId = Auth::id();
     	$userModel = $this->user->find($userId);   
        
    	$rules = [
            'email' => [
            	'required',
                'email',
                'max:255',
                function($attribute, $value, $fail) use($userModel) {
                    if ($value != $userModel->email) {
                        return $fail('登録された「メールアドレス」ではないようです。');
                    }
                },
            ],
            'password' => [
            	'required',
                'min:8',
                function($attribute, $value, $fail) use($userModel) {
                    if (! Hash::check($value, $userModel->password)) {
                        return $fail('登録された「パスワード」ではないようです。');
                    }
                },
            ],
        ];
        
        $messages = [
//            'title.required' => '「商品名」を入力して下さい。',

        ];
        
        $this->validate($request, $rules, $messages);
        $data = $request->all();
        
        
        //Member/クレカ登録削除
        $delMemberErrors = null;
        $delCardErrors = null;
        
        if(isset($userModel->member_id)) {
        	
            //クレカ削除 ================================
            if($userModel->card_regist_count) {
            	
                $count = $userModel->card_regist_count;
                $num = 0;
                
                while($num < $count) {
                    $dCardDatas = [
                        'SiteID' => $this->gmoId['siteId'],
                        'SitePass' => $this->gmoId['sitePass'],
                        'MemberID' => $userModel->member_id,
                        //'MemberID' => 11111,
                        'SeqMode' => 0, //論理モードで
                        'CardSeq' => $num, //論理モードを繰り返すのでseqは毎回0になる
                    ];
                    
                    $dCardResponse = Ctm::cUrlFunc("DeleteCard.idPass", $dCardDatas);
                    
                    //正常：CardSeq=0|1|2|3|4&DefaultFlag=0|0|0|0|0&CardName=||||&CardNo=*************111|*************111|*************111|*************111|*************111&Expire=1905|1904|1908|1907|1910&HolderName=||||&DeleteFlag=0|0|0|0|0
                    $cardArr = explode('&', $dCardResponse);
                    
                    foreach($cardArr as $res) {
                        $arr = explode('=', $res);
                        $delCardDatas[$arr[0]][$num] = explode('|', $arr[1]);
                    }
                    
                    
                    //$userRegResponse Error処理をここに ***********
                    if(array_key_exists('ErrCode', $delCardDatas)) {
                        $delCardErrors .= "<br>";
                        $delCardErrors .= '[mp-5501-Seq:'. $num .'-'; //cardSeqナンバーをエラーに付ける
                        $delCardErrors .= implode('|', $delCardDatas['ErrInfo'][$num]);
                        $delCardErrors .= ']';
                    }
                    
                    $num++;
                }

            }
            
            //Member削除 ======================================
        	$dMemberDatas = [
                'SiteID' => $this->gmoId['siteId'],
                //'SiteID' => 11111,
                'SitePass' => $this->gmoId['sitePass'],
                'MemberID' => $userModel->member_id,
            ];
            
            $dMemberResponse = Ctm::cUrlFunc("DeleteMember.idPass", $dMemberDatas);
            
            //正常：CardSeq=0|1|2|3|4&DefaultFlag=0|0|0|0|0&CardName=||||&CardNo=*************111|*************111|*************111|*************111|*************111&Expire=1905|1904|1908|1907|1910&HolderName=||||&DeleteFlag=0|0|0|0|0
            $cardArr = explode('&', $dMemberResponse);
            
            foreach($cardArr as $res) {
                $arr = explode('=', $res);
                $delMemberDatas[$arr[0]] = explode('|', $arr[1]);
            }
            
            
            //$userRegResponse Error処理をここに ***********
            if(array_key_exists('ErrCode', $delMemberDatas)) {
                $delMemberErrors .= "<br>";
                $delMemberErrors .= '[mp-5601-'; //cardSeqナンバーをエラーに付ける
                $delMemberErrors .= implode('|', $delMemberDatas['ErrInfo']);
                $delMemberErrors .= ']';
            }
        
            
        }
        
        
        //UserNoregistに移していたが->中止 -------------------------------
//        $userArr = $userModel->toArray();
//        $userArr['active'] = 2;
//        $this->userNor->create($userArr);
        
        //Userから消す->だったが中止しactiveを0にする ----------------------
//        $saleRels = $this->saleRel->where(['is_user'=>1, 'user_id'=>$userId])->get();
//        
//        if(count($saleRels)) {
//        	foreach($saleRels as $saleRel) {
//        		$saleRel->is_user = 0;
//                $saleRel->save();
//            }
//        }
        
        Auth::logout();
        //$userModel->delete();
        $userModel->active = 0;
        $userModel->save();

		$isMypage = 2;
        
        if(isset($delCardErrors) || isset($delMemberErrors) ) {
        	$status = "会員の退会手続きが完了しましたが、以下についてご確認下さい。"; 
        }
        else {
  			$status = "会員の退会手続きが完了しました。<br>HOMEへお戻り下さい。"; 
        }     
     	
        return view('mypage.formEnd', ['isMypage'=>$isMypage, 'status'=>$status, 'delCardErrors'=>$delCardErrors, 'delMemberErrors'=>$delMemberErrors, ]);   
    }
    
    
    
    public function favorite()
    {
    	$user = $this->user->find(Auth::id());
     	
        $imgIds = $this->favorite->where(['user_id'=>$user->id])->get()->map(function($obj){
         	return $obj->img_id;
        })->all();
      
      	$imgs = $this->userImg->whereIn('id', $imgIds)->where($this->whereArr)->orderBy('id', 'desc')->paginate($this->perPage); 
       
//       	foreach($imgs as $img) {
//        	$fav = $this->favorite->where(['user_id'=>$user->id, 'item_id'=>$item->id])->first();
//            
//         	if($fav->sale_id) {
//          		$item->saleDate = $this->sale->find($fav->sale_id)->created_at;
//          	}
//            else {
//            	$item->saleDate = 0;
//            }       
//        	//$item->saled = 1;
//        }      
//       
//       	$cates = $this->category;   
      
        return view('mypage.favorite', ['user'=>$user, 'imgs'=>$imgs,/* 'cates'=>$cates,*/ ]);   
    }
    
    public function follow()
    {
    	$user = $this->user->find(Auth::id());
     	
        $follows = $this->follow->where(['user_id'=>$user->id])->paginate($this->perPage);
             
//       	foreach($items as $item) {
//        	$fav = $this->favorite->where(['user_id'=>$user->id, 'item_id'=>$item->id])->first();
//            
//         	if($fav->sale_id) {
//          		$item->saleDate = $this->sale->find($fav->sale_id)->created_at;
//          	}
//            else {
//            	$item->saleDate = 0;
//            }       
//        	//$item->saled = 1;
//        }      
//       
//       	$cates = $this->category;   
      
        return view('mypage.follow', ['user'=>$user, 'follows'=>$follows, ]);   
    }
    
    
    
    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
