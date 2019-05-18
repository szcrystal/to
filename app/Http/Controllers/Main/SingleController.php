<?php

namespace App\Http\Controllers\Main;

use App\Tag;
use App\TagRelation;
use App\User;
use App\UserImg;
use App\Favorite;
use App\Good;

use App\ItemUpper;
use App\ItemUpperRelation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

use Auth;
use Ctm;
use Cookie;

class SingleController extends Controller
{
    public function __construct(Tag $tag, TagRelation $tagRel, UserImg $userImg, User $user, Favorite $favorite, Good $good)
    {
        //$this->middleware('search');
        
        $this->tag = $tag;
        $this->tagRel = $tagRel;
        $this->user = $user;
        $this->userImg = $userImg;
        
        $this->favorite = $favorite;
        $this->good = $good;
        
//        $this->tag = $tag;
//        $this->tagRelation = $tagRelation;
//        $this->tagGroup = $tagGroup;
//        $this->category = $category;
//        $this->item = $item;
//        $this->fix = $fix;
//        $this->totalize = $totalize;
//        $this->totalizeAll = $totalizeAll;
        
        $this->whereArr = ['open_status'=>1]; //こことSingleとSearchとCtm::isPotParentAndStockにある
        
        $this->itemPerPage = 15;
        
    }
    
    public function index($id)
    {
        $userImg = $this->userImg->find($id);
        $user = $this->user->find($userImg->user_id);
        
        $whereArr = $this->whereArr;
        
        if(!isset($userImg)) {
            abort(404);
        }
        else {
//            if($item->is_potset || ! $item->open_status) // || $item->is_secret
//            	abort(404);
        }
        
/*
        $cate = $this->category->find($item->cate_id);
        $subCate = $this->subCate->find($item->subcate_id);
        

        //ポットセットがある場合
        $potWhere = ['open_status'=>1, 'is_potset'=>1, 'pot_parent_id'=>$item->id];
        
        if(isset($item->pot_sort) && $item->pot_sort != '') {
        	$potSorts = $item->pot_sort;
        	$potSets = $this->item->where($potWhere)->orderByRaw("FIELD(id, $potSorts)")->get();
        }
        else {
        	$potSets = $this->item->where($potWhere)->orderBy('pot_count', 'asc')->get();
        }

        
        //Other Atcl
        $otherItem = $this->item->where($whereArr)->whereNotIn('id', [$id])->orderBy('created_at','DESC')->take(5)->get();
*/
        
        //Tag
        $tags = null;
        $tagRels = array();
        $sortIDs = array();
        
        $tagRels = $this->tagRel->where('img_id', $userImg->id)->orderBy('sort_num','asc')->get()->map(function($obj){
            return $obj->tag_id;
        })->all();
        
        if(count($tagRels) > 0) { //tagのget ->main.shared.tagの中でも指定しているのでここでは不要だが入れておく
			$sortIDs = implode(',', $tagRels);
        	$tags = $this->tag->whereIn('id', $tagRels)->orderByRaw("FIELD(id, $sortIDs)")->get();
        }


/*        
        //商品画像
        $imgsPri = $this->itemImg->where(['item_id'=>$id, 'type'=>1])->orderBy('number', 'asc')->get();
        //セカンド画像
        $imgsSec = $this->itemImg->where(['item_id'=>$id, 'type'=>2])->orderBy('number', 'asc')->get();
*/        
        
        //お気に入り確認
        $isFav = 0;
        if(Auth::check()) {
	        $fav = $this->favorite->where(['user_id'=>Auth::id(), 'img_id'=>$id])->first();
        	
            if(isset($fav)) $isFav = 1;   
        }
        
        $isGood = 0;
        if(Auth::check()) {
	        $good = $this->good->where(['user_id'=>Auth::id(), 'img_id'=>$id])->first();
        	
            if(isset($good)) $isGood = 1;   
        }
        
        
        
        //View Count
        $userImg->timestamps = false;
        $userImg->increment('view_count');

/*        
        //レコメンド ===========================
        //同梱包可能商品レコメンド -> 同じ出荷元で同梱包可能なもの
        $isOnceItems = null;
        $recomCateItems = null;
        $recomCateRankItems = null;
        $recommends = null;
        
        $getNum = Ctm::isAgent('sp') ? 6 : 6;
        $chunkNum = $getNum/2;
        
        if($item->is_once) {
        	$isOnceItems = $this->item->whereNotIn('id', [$item->id])->where($whereArr)->where(['consignor_id'=>$item->consignor_id, 'is_once'=>1, 'is_once_recom'=>0])->inRandomOrder()->take($getNum)->get()->chunk($chunkNum);
            //->inRandomOrder()->take()->get() もあり クエリビルダに記載あり
        }
        
        // レコメンド：同カテゴリー
        $recomCateItems = $this->item->whereNotIn('id', [$item->id])->where($whereArr)->where(['cate_id'=>$item->cate_id])->inRandomOrder()->take($getNum)->get()->chunk($chunkNum);
        
        // レコメンド：同カテゴリーのランキング
        $recomCateRankItems = $this->item->whereNotIn('id', [$item->id])->where($whereArr)->where(['cate_id'=>$item->cate_id])->orderBy('view_count', 'desc')->take($getNum)->get()->chunk($chunkNum);
        
        
        //Recommend レコメンド 先頭タグと同じものをレコメンド ==============
        //$getNum = Ctm::isAgent('sp') ? 3 : 3;
        
        if(isset($tagRels[1])) {
        	$ar = [$tagRels[1]];
            
            if(isset($tagRels[2])) {
            	$ar[] = $tagRels[2];
            }
            
            if(isset($tagRels[3])) {
            	$ar[] = $tagRels[3];
            }
            
        	$idWithTag = $this->tagRel->whereIn('tag_id', $ar)->get()->map(function($obj){
            	return $obj->item_id;
            })->all(); 
            
//            $tempIds = $idWithTag;
//            $tempIds[] = $item->id;
            
            $idWithCate = $this->item
            	//->whereNotIn('id', $tempIds)
                ->where('subcate_id', $item->subcate_id)->get()->map(function($obj){
            		return $obj->id;
            })->all();
            
            $res = array_merge($idWithTag, $idWithCate);
            $res = array_unique($res); //重複要素を削除
            
			//shuffle($res);
            //$res = array_rand($res, 5);
//            print_r($res);
//            exit;
            
            $recommends = $this->item->whereNotIn('id', [$item->id])->whereIn('id', $res)->where($whereArr)->inRandomOrder()->take($getNum)->get()->chunk($chunkNum);
            //->inRandomOrder()->take()->get() もあり クエリビルダに記載あり
        }
        else {
        	$recommends = $this->item->whereNotIn('id', [$item->id])->where($whereArr)->where(['subcate_id'=>$item->subcate_id])->inRandomOrder()->take($getNum)->get()->chunk($chunkNum);
            //->inRandomOrder()->take()->get() もあり クエリビルダに記載あり
        }
        
//        print_r($recommends);
//        exit;

		$recomArr = [
        	'同梱包可能なおすすめ商品' => $isOnceItems,
            'この商品を見た人におすすめの商品' => $recomCateItems,
            'カテゴリーランキング' => $recomCateRankItems,
            '他にもこんな商品が買われています' => $recommends,
        ];
*/
        
/*        
        //Cache 最近見た ===================
        $cookieArr = array();
        $cacheItems = null;
        $getNum = Ctm::isAgent('sp') ? 8 : 8;
        
        
        $cookieIds = Cookie::get('item_ids');
//        echo $cookieIds;
//        exit;
        
        if(isset($cookieIds) && $cookieIds != '') {
	        $cookieArr = explode(',', $cookieIds); 
            
	        $chunkNum = Ctm::isAgent('sp') ? $getNum/2 : $getNum;
          	
	        $cacheItems = $this->item->whereIn('id', $cookieArr)->whereNotIn('id', [$item->id])->where($whereArr)->orderByRaw("FIELD(id, $cookieIds)")->take($getNum)->get()->chunk($chunkNum);
		}
        
        if(! in_array($item->id, $cookieArr)) { //配列にidがない時 or cachIdsが空の時
        	$count = array_unshift($cookieArr, $item->id); //配列の最初に追加
         	
          	if($count > 16) {
            	$cookieArr = array_slice($cookieArr, 0, 16); //16個分を切り取る
        	} 
        }
        else { //配列にidがある時 
        	$index = array_search($item->id, $cookieArr); //key取得
            
            //$split = array_splice($cacheIds, $index, 1); //keyからその要素を削除
            unset($cookieArr[$index]);
            $cookieArr = array_values($cookieArr);
            
        	$count = array_unshift($cookieArr, $item->id); //配列の最初に追加
        }
        
        $cookieIds = implode(',', $cookieArr);
        
        Cookie::queue(Cookie::make('item_ids', $cookieIds, env('COOKIE_TIME', 43200) ));
*/        
        
        /*
        if(cache()->has('item_ids')) {
        	
        	$cacheIds = cache()->pull('item_ids'); //pullで元キャッシュを一旦削除する必要がある
            $caches = implode(',', $cacheIds); //順を逆にする
            
            $chunkNum = Ctm::isAgent('sp') ? $getNum/2 : $getNum;
          	
            $cacheItems = $this->item->whereIn('id', $cacheIds)->whereNotIn('id', [$item->id])->where($whereArr)->orderByRaw("FIELD(id, $caches)")->take($getNum)->get()->chunk($chunkNum);
            
//            print_r($cacheItems);
//            exit;  
        }
        
        if(! in_array($item->id, $cacheIds)) { //配列にidがない時 or cachIdsが空の時
        	$count = array_unshift($cacheIds, $item->id); //配列の最初に追加
         	
          	if($count > 16) {
            	$cacheIds = array_slice($cacheIds, 0, 16); //16個分を切り取る
        	}      
        }
        else { //配列にidがある時  
        	//print_r($cacheIds);   
                   
        	$index = array_search($item->id, $cacheIds); //key取得
            
            //$split = array_splice($cacheIds, $index, 1); //keyからその要素を削除
            unset($cacheIds[$index]);
            $cacheIds = array_values($cacheIds);
//            print_r($cacheIds);
//            
//            cache()->forget('cacheIds');
//            cache(['cacheIds'=>$cacheIds], env('CACHE_TIME', 360));
//            print_r(cache('cacheIds'));

            //exit;
            
        	$count = array_unshift($cacheIds, $item->id); //配列の最初に追加
        }

		cache()->forget('item_ids');
        cache(['item_ids'=>$cacheIds], env('CACHE_TIME', 43200)); //put 上書きではなく後ろに追加されている
        */


		//ItemUpper
//        $upperRels = null;
//        $upperRelArr = array();
//        $upper = $this->upper->where(['parent_id'=>$id, 'type_code'=>'item', 'open_status'=>1])->first();
//		
//        if(isset($upper)) {
//        	$upperRels = $this->upperRel->where(['upper_id'=>$upper->id, ])->orderBy('sort_num', 'asc')->get();
//            
//            if($upperRels->isNotEmpty()) {
//            	foreach($upperRels as $upperRel) {
//                	if($upperRel->is_section) {
//                    	$upperRelArr[$upperRel->block]['section'] = $upperRel;
//                    }
//                    else {
//                    	$upperRelArr[$upperRel->block]['block'][] = $upperRel;
//                    }
//                }
//            }
//        }
        
        //$upperRelArr = Ctm::getUpperArr($id, 'item');

		
        $metaTitle = isset($userImg->meta_title) ? $userImg->meta_title : $userImg->title;
        $metaDesc = $userImg->meta_description;
        $metaKeyword = $userImg->meta_keyword;
        
        
        return view('main.home.single', ['userImg'=>$userImg, 'user'=>$user, 'tags'=>$tags, 'isFav'=>$isFav, 'isGood'=>$isGood, 'metaTitle'=>$metaTitle, 'metaDesc'=>$metaDesc, 'metaKeyword'=>$metaKeyword, 'type'=>'single']);
    }
    
    
    public function postForm(Request $request)
    {
    	$data = $request->all();
     
     	$buyItem = $this->item->find($data['item_id']);
      
         
        return view('main.cart.index', ['data'=>$data ]);
    }
    
    
    public function postCart(Request $request)
    {
    	$data = $request->all();
     
        $buyItem = $this->item->find($data['item_id']);
        
//        $per = env('TAX_PER');
//        $per = $per/100;
//        
//        $tax = floor($item->price * $per);
//        $price = $item->price + $tax;
     	
      	//$title = $this->item->find($data['item_id'])->title;   
      //ここでsessionに入れる必要がある
         
    	
        return view('main.cart.single', ['buyItem'=>$buyItem, 'tax'=>$data['tax'], 'count'=>$data['count'], 'name'=>$data['name'] ]); 
        
    }
    
    //お気に入り ajax
    public function postFavGoodScript(Request $request)
    {
        $imgId = $request->input('imgId');
        $type = $request->input('type');
        
        $isOn = $request->input('isOn');
        
        //$user = $this->user->find(Auth::id());
        $userId = Auth::id();
        $str = '';
        
        if($type == 'favorite') {
        	$model = $this->favorite;
            $typeStr = 'お気に入り';
        }
        else {
        	$model = $this->good;
            $typeStr = 'いいね';
        }
        
		//Favorite Save ==================================================
        //foreach($data['spare_count'] as $count) {
                        
            if(!$isOn) { //お気に入り解除の時
                $favModel = $model->where(['user_id'=>$userId, 'img_id'=>$imgId])->first();
                
                if($favModel !== null) {
                    $favModel ->delete();
                }
                
                $str = $typeStr . "から削除されました";
            }
            else {
                    
                $favModel = $model->updateOrCreate(
                    ['user_id'=>$userId, 'img_id'=>$imgId],
                    [
                        'user_id'=>$userId,
                        'img_id'=>$imgId,
//                            'type' => 1,
//                            'number'=> $count+1,
                    ]
                );
				
    			$str = $typeStr . "に登録されました";       
            }
            
        //} //foreach
        
//        $num = 1;
//        $spares = $this->itemImg->where(['item_id'=>$itemId, 'type'=>1])->get();
        
        //Snapのナンバーを振り直す
//        foreach($spares as $spare) {
//            $spare->number = $num;
//            $spare->save();
//            $num++;
//        }
        

        return response()->json(['str'=>$str]/*, 200*/); //200を指定も出来るが自動で200が返される  
          //return view('dashboard.script.index', ['val'=>$val]);
        //return response()->json(array('subCates'=> $subCates)/*, 200*/);
    }
    
    public function endCart()
    {
    	return view('main.cart.end');
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
