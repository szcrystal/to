<?php

namespace App\Http\Controllers\MyPage;

use App\User;
use App\UserImg;
use App\Category;
use App\Tag;
use App\TagRelation;
use App\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

class UpController extends Controller
{
    public function __construct(User $user, UserImg $userImg, Category $cate, Tag $tag, TagRelation $tagRelation, Setting $setting)
    {
        $this -> middleware('auth');
    
    	$this->user = $user;
    	$this->userImg = $userImg;
        
        $this->cate = $cate;
        $this->tag = $tag;
        $this->tagRel = $tagRelation;
        
        $this->set = $setting->first();
        
        
        $this->whereArr = ['open_status'=>1];
        $this->perPage = 20;
    
    }
    
    
    public function index()
    {
        $uId = Auth::id();
        $user = $this->user->find($uId);
        
        $userImgs = $this->userImg->where('user_id',$uId)->paginate($this->perPage);
        
        $primaryCount = 1;
        
        //Tag
        $tagNames = array();
        $allTags = array();
        
//        $tagNames = $this->tagRel->where(['img_id'=>$id])->orderBy('sort_num', 'asc')->get()->map(function($item) {
//            return $this->tag->find($item->tag_id)->name;
//        })->all();
//        
        $allTags = $this->tag->get()->map(function($item){
            return $item->name;
        })->all();
        
        return view('mypage.ups', ['user'=>$user, 'userImgs'=>$userImgs, 'primaryCount'=>$primaryCount, 'tagNames'=>$tagNames, 'allTags'=>$allTags, ]);
    }

    
    public function show($imgId)
    {
        $userImg = $this->userImg->find($imgId);
        $userId = Auth::id();
        
        if($userImg->user_id != $userId) {
        	abort(404);
        }
        
        
//        $cates = $this->category->all();
//        $subcates = $this->categorySecond->where(['parent_id'=>$item->cate_id])->get();
//        $consignors = $this->consignor->all();
        
        //$dgs = $this->dg->where('open_status', 1)->get();
        
        $spares = $this->userImg->where(['id'=>$imgId, 'user_id'=>$userId, /*'type'=>1*/])->get();
//        print_r($spares);
//        exit;
        
        //$snaps = $this->itemImg->where(['item_id'=>$id, 'type'=>2])->get();
        
        //$users = $this->user->where('active',1)->get();
        
        //Cate
        $allCates = $this->cate->where($this->whereArr)->get();
        
		$tagNames = $this->tagRel->where(['img_id'=>$imgId])->orderBy('sort_num', 'asc')->get()->map(function($obj) {
            return $this->tag->find($obj->tag_id)->name;
        })->all();
        
        $allTags = $this->tag->get()->map(function($obj){
            return $obj->name;
        })->all();
        
        $setting = $this->set;
        $primaryCount = 1;
        
//        $primaryCount = $setting->snap_primary;
//        $imgCount = $setting->snap_secondary;
        
        //$icons = $this->icon->all();
        
        return view('mypage.upform', ['userImg'=>$userImg, /*'cates'=>$cates, 'subcates'=>$subcates, 'consignors'=>$consignors, 'dgs'=>$dgs,*/'tagNames'=>$tagNames, 'allCates'=>$allCates, 'allTags'=>$allTags, 'spares'=>$spares, /*'snaps'=>$snaps, */'primaryCount'=>$primaryCount, 'editId'=>$imgId, 'edit'=>1]);
    }
    
    
    public function create()
    {
        $uId = Auth::id();
        $user = $this->user->find($uId);
        
        $userImgs = $this->userImg->where('user_id',$uId)->get();
        
        $primaryCount = 1;
        
        //Cate
        $allCates = $this->cate->where($this->whereArr)->get();
        
        //Tag
        $tagNames = array();
        $allTags = array();
        
//        $tagNames = $this->tagRel->where(['img_id'=>$id])->orderBy('sort_num', 'asc')->get()->map(function($item) {
//            return $this->tag->find($item->tag_id)->name;
//        })->all();
//        
        $allTags = $this->tag->get()->map(function($item){
            return $item->name;
        })->all();
        
        //$editId = 0;
        
        return view('mypage.upForm', ['user'=>$user, 'userImgs'=>$userImgs, 'primaryCount'=>$primaryCount,/* 'editId'=>$editId,*/ 'tagNames'=>$tagNames, 'allCates'=>$allCates, 'allTags'=>$allTags, ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $editId = $request->has('edit_id') ? $request->input('edit_id') : 0;
        $userId = Auth::id();
        
    	$rules = [
//        	'number' => 'required|unique:items,number,'.$editId,
//            'title' => 'required|max:255',
            'cate_id' => 'required',
//            
//            'pot_sort' => [
//            	'nullable',
//            	'max:255',
//                function($attribute, $value, $fail) {
//                    if (strpos($value, '、') !== false) {
//                        return $fail('「子ポット並び順」に全角のカンマがあります。');
//                    }
//                    else {
//                    	$nums = explode(',', $value);
//                        foreach($nums as $num) {
//                        	if(! is_numeric($num)) {
//                            	return $fail('「子ポット並び順」に全角の数字があります。');
//                            }
//                        }
//                    }
//                }
//            ],
            
            //'dg_id' => 'required',
//            'dg_id' => [ //dgに送料が入力されていない時
//            	'required',
//                
//                function($attribute, $value, $fail) {
//                	$result = 1;
//                    
//            		$feeObjs = $this->dgRel->where(['dg_id'=>$value])->get();
//                    
//                    if($feeObjs->isEmpty()) {
//                    	$result = 0;
//                    }
//                    else {
//                        foreach($feeObjs as $obj) {
//                            if($obj->fee === null || $obj->fee == '') {
//                                $result = 0;
//                                break;
//                            }
//                        }
//                    }
//                    
//                	if(! $result) {
//                		return $fail('「配送区分」の都道府県別送料が未入力です。配送区分マスターを確認して下さい。');
//                    }
//                },
//            ],
//            
//            'factor' => 'required|numeric',
//            
//            'price' => 'required|numeric',
//            'cost_price' => 'nullable|numeric',
//            'sale_price' => 'nullable|numeric',
//            'stock' => 'nullable|numeric',
//            'stock_reset_month' => [
//                function($attribute, $value, $fail) use($request) {
//                    if($value == '') {
//                        if($request->input('stock_type') == 1) {
//                            return $fail('「在庫入荷月」を指定して下さい。');
//                        } 
//                    }
//                    elseif(! is_numeric($value)) {
//                    	return $fail('「在庫入荷月」は半角数字を入力して下さい。');
//                    }
//                    elseif ($value < 1 || $value > 12) {
//                        return $fail('「在庫入荷月」は正しい月を入力して下さい。');
//                    }
//                },
//            ],
//            'stock_reset_count' => 'nullable|numeric',
//            'point_back' => 'nullable|numeric',
//            
//            'pot_parent_id' =>'required_with:is_potset|nullable|numeric',
//            'pot_count' =>'required_with:is_potset|nullable|numeric',
            
            //'main_img' => 'filenaming',
        ];
        
//        if($request->has('is_potset')) {
//        	unset($rules['cate_id']);
//        }
        
        $messages = [
         	'title.required' => '「商品名」を入力して下さい。',
            'cate_id.required' => '「カテゴリー」を選択して下さい。',
            
            //'post_thumb.filenaming' => '「サムネイル-ファイル名」は半角英数字、及びハイフンとアンダースコアのみにして下さい。',
            //'post_movie.filenaming' => '「動画-ファイル名」は半角英数字、及びハイフンとアンダースコアのみにして下さい。',
            //'slug.unique' => '「スラッグ」が既に存在します。',
        ];
        
        $this->validate($request, $rules, $messages);
        
        $data = $request->all();
//        print_r($data);
//        exit;
        
//        print_r($data['icons']);
//		echo implode(',', $data['icons']);
//        exit;
        
        $data['user_id'] = $userId;
        
        //status
//        $data['open_status'] = isset($data['open_status']) ? 0 : 1;
//        $data['is_secret'] = isset($data['is_secret']) ? 1 : 0;
        
        //stock_show
//        $data['is_ensure'] = isset($data['is_ensure']) ? 1 : 0;
//        $data['is_delifee'] = isset($data['is_delifee']) ? 1 : 0;
//        $data['stock_show'] = isset($data['stock_show']) ? 1 : 0;
//        $data['farm_direct'] = isset($data['farm_direct']) ? 1 : 0;
//        $data['is_once'] = isset($data['is_once']) ? 1 : 0;
//        $data['is_once_recom'] = isset($data['is_once_recom']) ? 1 : 0;
//        $data['is_delifee_table'] = isset($data['is_delifee_table']) ? 1 : 0;
//        $data['is_potset'] = isset($data['is_potset']) ? 1 : 0;
//        
//        $data['icon_id'] = isset($data['icons']) ? implode(',', $data['icons']) : '';
        
        //上書き制御用データ
//        $forceUp = isset($data['force_up']) ? 1 : 0;
//        $data['admin_id'] = Auth::guard('admin')->id();
        
        //ポットセットの時、親にtrueをセットする->不要にした
//        if($data['is_potset']) {
//            $pItem = $this->item->find($data['pot_parent_id']);
//            
//            if(! $pItem->is_pot_parent) {
//            	$pItem->is_pot_parent = 1;
//            	$pItem->save();
//            }
//        }
        
        if($editId) { //update（編集）の時
            $status = '投稿が更新されました！';
            $userImg = $this->userImg->find($editId);
            
            /*
            //上書き更新の制御 ------------
            if(! $forceUp) {
            	
                $isAdmin = 0;
                $errorStr = '他の管理者さんが、';
                
                if(isset($item->admin_id)) { //管理者が自分でないことを確認 自分の場合は上書き制限をかけない
                	$admin = $this->admin->find($item->admin_id);
                    
                    $errorStr = $admin->name . 'さんが、';
                    $isAdmin = $admin->id == $data['admin_id'];
                }
                
                if(! $isAdmin) { //前回更新が自分でなければ上書き制限をかける
                    $upDate = new DateTime($item->updated_at);
                    $nowDate = new DateTime();
                    
                    $diff = $nowDate->diff($upDate); //$nowDateにmodifyをした後だと狂うので先にdiffを取得しておく
                    //print_r($diff);
                    //exit;
                    
                    $rewriteTime = $this->setting->first()->rewrite_time;
                    $limitDate = $nowDate->modify('-'.$rewriteTime.' minutes'); // 制限時間のDate Objを取得
                    
                    //Timestamp比較で
                    if($limitDate->format('U') < $upDate->format('U')) { // or $limit->getTimestamp()

                        $minute = $diff->h ? ($diff->h * 60) + $diff->i : $diff->i; //差が1時間以上であれば分にして足す
                        
                        $errorStr .= $minute . '分前に更新しています。上書きする場合は「強制更新」をONにして更新して下さい。';
                        
                        return back()->withInput()->with('rewriteError', $errorStr);
                    }
                }
            }
            //上書き更新の制御 END ------------
            */

            //stockChange save 新着情報用
//            if($item->stock < $data['stock']) { //在庫が増えた時のみにしている 増えた時のみitemStockChangeにsave
////            	$this->itemSc->updateOrCreate( //データがなければ各種設定して作成
////                	['item_id'=>$item->id], 
////                    ['is_auto'=>0]
////                );
//                
//            	$itemSc = $this->itemSc->firstOrCreate( ['item_id'=>$item->id], ['is_auto'=>0]); //あれば取得、なければ作成
//                $itemSc->updated_at = date('Y-m-d H:i:s', time()); 
//                $itemSc->save();
//            }
            
            $userImg->update($data); //Item更新
            
        }
        else { //新規追加の時
            $status = '投稿が追加されました！';            
            //$item = $this->item;
            $userImg = $this->userImg->create($data); //userImg作成
            
            //stockChange save 新着情報用
            //$this->itemSc->create(['item_id'=>$item->id, 'is_auto'=>0]);
        }
        
        //potsetの時 parentのpot_parent_idに0をセットする
//        if($item->is_potset) {
//        	$parentItem = $this->item->find($item->pot_parent_id);
//            
//            if(isset($parentItem) && ! isset($parentItem->pot_parent_id)) {
//            	$parentItem->pot_parent_id = 0;
//            	$parentItem->save();
//            }
//        }
//        
//        $item->fill($data);
//        $item->save();
        $imgId = $userImg->id;
        

//        print_r($data['main_img']);
//        exit;
        
        //Main-img
        if(isset($data['del_mainimg']) && $data['del_mainimg']) { //削除チェックの時
            if($item->main_img !== null && $item->main_img != '') {
                Storage::delete($item->main_img); //Storageはpublicフォルダのあるところをルートとしてみる
                $item->main_img = null;
                $item->save();
            }
        }
        else {
            if(isset($data['main_img'])) {
                
                //$filename = $request->file('main_img')->getClientOriginalName();
                $filename = $data['main_img']->getClientOriginalName();
                $filename = str_replace(' ', '_', $filename);
                
                //$aId = $editId ? $editId : $rand;
                //$pre = time() . '-';
                $filename = 'item/' . $itemId . '/main/'/* . $pre*/ . $filename;
                //if (App::environment('local'))
                $path = $data['main_img']->storeAs('public', $filename);
                //else
                //$path = Storage::disk('s3')->putFileAs($filename, $request->file('thumbnail'), 'public');
                //$path = $request->file('thumbnail')->storeAs('', $filename, 's3');
                
                $item->main_img = $path;
                $item->save();
            }
        }
        
        
        //SpareImg Save ==================================================
        foreach($data['spare_count'] as $count) {
                        
            if(isset($data['del_spare'][$count]) && $data['del_spare'][$count]) { //削除チェックの時
                
                $spareModel = $this->userImg->where(['user_id'=>$userId, /*'type'=>1, */'sort_num'=>$count+1])->first();
                
                if($spareModel !== null) {
                	Storage::delete('public/'. $spareModel->img_path); //Storageはpublicフォルダのあるところをルートとしてみる
                    $spareModel ->delete();
                }
            
            }
            else {
            	//キャプションのupが必ず必要なので、画像数データを全て作成する
                $spareImg = $this->userImg->updateOrCreate(
                    //['user_id'=>$userId, /*'type'=>1, */'sort_num'=>$count+1],
                    ['id'=>$imgId, /*'type'=>1, 'sort_num'=>$count+1*/],
                    [
                        'user_id'=>$userId,
                        //'caption'=>$data['caption'][$count],
                        //'type' => 1,
                        'sort_num'=> $count+1,
                    ]
                );

                
            	if(isset($data['spare_thumb'][$count])) {
                    /*
                    $spareImg = $this->itemImg->updateOrCreate(
                        ['item_id'=>$itemId, 'type'=>1, 'number'=>$count+1],
                        [
                            'item_id'=>$itemId,
                            'caption'=>$data['caption'][$count],
                            'type' => 1,
                            'number'=> $count+1,
                        ]
                    );
                    */
                
                    $filename = $data['spare_thumb'][$count]->getClientOriginalName();
                    $filename = str_replace(' ', '_', $filename);
                    
                    //$aId = $editId ? $editId : $rand;
                    $pre = time() . '-';
                    $filename = 'user/' . $userId . '/post/' . $imgId . '/' . $pre . $filename;
                    //if (App::environment('local'))
                    $path = $data['spare_thumb'][$count]->storeAs('public', $filename);
                    //else
                    //$path = Storage::disk('s3')->putFileAs($filename, $request->file('thumbnail'), 'public');
                    //$path = $request->file('thumbnail')->storeAs('', $filename, 's3');
                
                    //$data['model_thumb'] = $filename;
                    
                    //$spareImg->img_path = $filename;
                    $spareImg->img_path = $path;
                    $spareImg->save();
                }
            }
            
        } //foreach
        
        $num = 1;
        $spares = $this->userImg->where(['user_id'=>$userId,/* 'type'=>1*/])->get();
        
        //Spareのナンバーを振り直す
        foreach($spares as $spare) {
            $spare->sort_num = $num;
            $spare->save();
            $num++;
        }
        
        //Spare END ===========================================
        
        
        /*
        //Snap Save ==================================================
        foreach($data['snap_count'] as $count) {
        
			
//            type:1->item spare
//            type:2->item snap(contents)
//            type:3->category
//            type:4->sub category
//            type:5->tag                              
           
 
            if(isset($data['del_snap'][$count]) && $data['del_snap'][$count]) { //削除チェックの時
                //echo $count . '/' .$data['del_snap'][$count];
                //exit;
                
                $snapModel = $this->itemImg->where(['item_id'=>$itemId, 'type'=>2, 'number'=>$count+1])->first();
                
                if($snapModel !== null) {
                	Storage::delete('public/'.$snapModel->img_path); //Storageはpublicフォルダのあるところをルートとしてみる
                    $snapModel ->delete();
                }
            
            }
            else {
            	if(isset($data['snap_thumb'][$count])) {
                    
                    $snapImg = $this->itemImg->updateOrCreate(
                        ['item_id'=>$itemId, 'type'=>2, 'number'=>$count+1],
                        [
                            'item_id'=>$itemId,
                            //'snap_path' =>'',
                            'type' => 2,
                            'number'=> $count+1,
                        ]
                    );

                    $filename = $data['snap_thumb'][$count]->getClientOriginalName();
                    $filename = str_replace(' ', '_', $filename);
                    
                    //$aId = $editId ? $editId : $rand;
                    //$pre = time() . '-';
                    $filename = 'item/' . $itemId . '/snap/' . $filename;
                    //if (App::environment('local'))
                    $path = $data['snap_thumb'][$count]->storeAs('public', $filename);
                    //else
                    //$path = Storage::disk('s3')->putFileAs($filename, $request->file('thumbnail'), 'public');
                    //$path = $request->file('thumbnail')->storeAs('', $filename, 's3');
                
                    //$data['model_thumb'] = $filename;
                    
                    $snapImg->img_path = $filename;
                    $snapImg->save();
                }
            }
            
        } //foreach
        
        $num = 1;
        $snaps = $this->itemImg->where(['item_id'=>$itemId, 'type'=>2])->get();
//            $snaps = $this->modelSnap->where(['model_id'=>$modelId])->get()->map(function($obj) use($num){
//                
//                return true;
//            });
        
        //Snapのナンバーを振り直す
        foreach($snaps as $snap) {
            $snap->number = $num;
            $snap->save();
            $num++;
        }
        
        //Snap END ===========================================
        */
        
//        print_r($data['tags']);
//        exit;
        
        //タグのsave動作
        if(isset($data['tags'])) {
            
            $tagArr = $data['tags'];
            
            //タグ削除の動作
            if(isset($editId)) { //編集時のみ削除されたタグを消す
            	//現在あるtagRelを取得
                $tagRelIds = $this->tagRel->where('img_id', $imgId)->get()->map(function($tagRelObj){
                    return $tagRelObj->tag_id;
                })->all();
                
                //入力されたtagのidを取得（新規のものは取得されない->する必要がない）
                $tagIds = $this->tag->whereIn('name', $tagArr)->get()->map(function($tagObj){
                    return $tagObj->id;
                })->all();
                
                //配列同士を比較(重複しないものは$tagRelIdsからreturnされる->これらが削除対象となる)
                $tagDiffs = array_diff($tagRelIds, $tagIds);
                
                //削除対象となったものを削除する
                if(count($tagDiffs) > 0) {
                    foreach($tagDiffs as $valTagId) {
                        $this->tagRel->where(['item_id'=>$itemId, 'tag_id'=>$valTagId])->first()->delete();
                    }
                }
            }
            
        	$num = 1;
            
            foreach($tagArr as $tag) {
                
                //Tagセット
                $setTag = Tag::firstOrCreate(['name'=>$tag]); //既存を取得 or なければ作成
                
                if(!$setTag->slug) { //新規作成時slugは一旦NULLでcreateされるので、その後idをセットする
                    $setTag->slug = $setTag->id;
                    $setTag->save();
                }
                
                $tagId = $setTag->id;
                $tagName = $tag;


                //tagIdがRelationになければセット ->firstOrCreate() ->updateOrCreate()
                $this->tagRel->updateOrCreate(
                    ['tag_id'=>$tagId, 'img_id'=>$imgId],
                    ['sort_num'=>$num]
                );

				$num++;
                
                //tagIdを配列に入れる　削除確認用
                //$tagIds[] = $tagId;
            }
        
        	/*
            //編集時のみ削除されたタグを消す
            if(isset($editId)) {
                //元々relationにあったtagがなくなった場合：今回取得したtagIdの中にrelationのtagIdがない場合をin_arrayにて確認
                $tagRels = $this->tagRelation->where('item_id', $itemId)->get();
                
                foreach($tagRels as $tagRel) {
                    if(! in_array($tagRel->tag_id, $tagIds)) {
                        $tagRel->delete();
                    }
                }
            }
            */
        }
        else { 
        	if(isset($editId)) {
        		$tagRels = $this->tagRel->where('img_id', $imgId)->delete();
            }
            
//            if(isset($tagRels)) {
//            	$tagRels
//            }
        }
        
        
        return redirect('mypage/ups/'. $imgId)->with('status', $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function show($id)
//    {
//        //
//    }

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
