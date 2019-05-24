<?php

namespace App\Http\Controllers\Main;

use App\Setting;
use App\UserImg;
use App\Tag;
use App\TagRelation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Ctm;

class HomeController extends Controller
{
    public function __construct(Setting $setting, UserImg $userImg, Tag $tag, TagRelation $tagRel/*Item $item, Category $category, CategorySecond $cateSec, Tag $tag, TagRelation $tagRel, Fix $fix, Setting $setting, ItemImage $itemImg, Favorite $favorite, ItemStockChange $itemSc, TopSetting $topSet, DeliveryGroup $dg, DeliveryGroupRelation $dgRel, Auth $auth*/)
    {
        //$this->middleware('search');
        
//        $this->item = $item;
//        $this->category = $category;
//        $this->cateSec = $cateSec;
//        $this->tag = $tag;
//        $this->tagRel = $tagRel;
//        $this->fix = $fix;
//
        $this->setting = $setting;
        $this->set = $this->setting->first();
        
        $this->userImg = $userImg;
        $this->tag = $tag;
        $this->tagRel = $tagRel;
//        $this->itemImg = $itemImg;
//        $this->favorite = $favorite;
//        $this->itemSc = $itemSc;
//        $this->topSet = $topSet;
//        
//        $this->dg = $dg;
//        $this->dgRel = $dgRel;
//                
//        //ここでAuth::check()は効かない
        $this->whereArr = ['open_status'=>1]; //こことSingleとSearchとCtm::isPotParentAndStockにある
                
        $this->perPage = Ctm::isAgent('sp') ? 21 : 20;
        
    }

    public function index()
    {
        
//        $request->session()->forget('item.data');
//        $request->session()->forget('all');

        //$cates = $this->category->all();
        
        $whereArr = $this->whereArr;
        
        $userImgs = $this->userImg->where($whereArr)->orderBy('view_count', 'desc')->get();
        
        $popTags = $this->tag->orderBy('view_count', 'asc')->take(20)->get();
        
//        $tagIds = TagRelation::where('item_id', 1)->get()->map(function($obj){
//            return $obj->tag_id;
//        })->all();
//        
//        $strs = implode(',', $tagIds);
//        
//        $placeholder = '';
//        foreach ($tagIds as $key => $value) {
//           $placeholder .= ($key == 0) ? $value : ','.$value;
//        }
        //exit;
        
    //    $strs = "FIELD(id, $strs)";
    //    echo $strs;
        //exit;
        
        //->orderByRaw("FIELD(id, $sortIDs)"
//        $tags = Tag::whereIn('id', $tagIds)->orderByRaw("FIELD(id, $placeholder)")->take(2)->get();
//        print_r($tags);
//        exit;
        
//        $stateObj = null;
//        //$stateName = '';
//        
//        if(isset($state)) {
//            $stateObj = $this->state->where('slug', $state)->get()->first();
//            $whereArr['state_id'] = $stateObj->id;
//            $whereArrSec['state_id'] = $stateObj->id;
//            //$stateName = $stateObj->name;
//        }

/*
		//Carousel
        $caros = $this->itemImg->where(['item_id'=>9999, 'type'=>6])->inRandomOrder()->get();

		//FirstItem =======================
        $getNum = Ctm::isAgent('sp') ? 3 : 4;
		
        //New
        $newItems = null;
        
        $scIds = $this->itemSc->orderBy('updated_at','desc')->get()->map(function($isc){
        	return $isc->item_id;
        })->all();
        
        if(count($scIds) > 0) {
            $scIdStr = implode(',', $scIds);
            $newItems = $this->item->whereIn('id', $scIds)->where($whereArr)->orderByRaw("FIELD(id, $scIdStr)")->take($getNum)->get()->all();
        }
        
        //Ranking
        $rankItems = $this->item->where($whereArr)->orderBy('sale_count', 'desc')->take($getNum)->get()->all();
        
        //Recent 最近見た
        $cookieArr = array();
        $cookieItems = null;
        //$getNum = Ctm::isAgent('sp') ? 6 : 7;
        
        $cookieIds = Cookie::get('item_ids');
        
        if(isset($cookieIds) && $cookieIds != '') {
            $cookieArr = explode(',', $cookieIds); //orderByRowに渡すものはString
          	$cookieItems = $this->item->whereIn('id', $cookieArr)->where($whereArr)->orderByRaw("FIELD(id, $cookieIds)")->take($getNum)->get()->all();  
        }
        
        
//        if(cache()->has('item_ids')) {
//        	
//        	$cacheIds = cache('item_ids');
//            
//            $caches = implode(',', $cacheIds); //orderByRowに渡すものはString
//            
//          	$cacheItems = $this->item->whereIn('id', $cacheIds)->where($whereArr)->orderByRaw("FIELD(id, $caches)")->take($getNum)->get()->all();  
//        }
        
        
        //array
        $firstItems = [
        	'新着情報'=> $newItems,
            '人気ランキング'=> $rankItems,
            '最近チェックしたアイテム'=> $cookieItems,
        ];
        //FirstItem END ================================
        
        
        //おすすめ情報 RecommendInfo (cate & cateSecond & tag)
        $tagRecoms = $this->tag->where(['is_top'=>1])->orderBy('updated_at', 'desc')->get()->all();
        $cateRecoms = $this->category->where(['is_top'=>1])->orderBy('updated_at', 'desc')->get()->all();
        $subCateRecoms = $this->cateSec->where(['is_top'=>1])->orderBy('updated_at', 'desc')->get()->all();
        
        $res = array_merge($tagRecoms, $cateRecoms, $subCateRecoms);
        
//        $books = array(
//        	$tagRecoms,
//            $cateRecoms,
//            $subCateRecoms
//        );
        
        $collection = collect($res);
        $allRecoms = $collection->sortByDesc('updated_at');
        
//        print_r($allRecoms);
//        exit;

        //$allRecoms = $this->item->where($whereArr)->orderBy('created_at', 'desc')->take(10)->get(); 

		//category
        $itemCates = array();
        foreach($cates as $cate) { //カテゴリー名をkeyとしてatclのかたまりを配列に入れる
        
            $whereArr['cate_id'] = $cate->id;
            
            $as = $this->item->where($whereArr)->orderBy('created_at','DESC')->take(8)->get()->all();
            
            if(count($as) > 0) {
                $itemCates[$cate->id] = $as;
            }
        }
        
//        $items = $this->item->where(['open_status'=>1])->orderBy('created_at','DESC')->get();
//        $items = $items->groupBy('cate_id')->toArray();

		//head news
        $setting = $this->topSet->get()->first();
        
		$newsCont = $setting->contents;
*/
		
        $metaTitle = $this->set->meta_title;
        $metaDesc = $this->set->meta_description;
        $metaKeyword = $this->set->meta_keyword;
        
        //For this is top
        $isTop = 1;
        

        return view('main.home.index', ['userImgs'=>$userImgs, 'popTags'=>$popTags,'metaTitle'=>$metaTitle, 'metaDesc'=>$metaDesc, 'metaKeyword'=>$metaKeyword, 'isTop'=>$isTop,]);
    }

    
    public function tagIndex($slug)
    {
    	$tag = $this->tag->where('slug', $slug)/*->where($this->whereArr)*/->first();
        
        if(!isset($tag)) {
            abort(404);
        }
    	
        $imgIds = TagRelation::where('tag_id', $tag->id)->get()->map(function($obj){
            return $obj->img_id;
        })->all();
        
        $userImgs = $this->userImg->whereIn('id', $imgIds)->where($this->whereArr)->orderBy('created_at', 'desc')->paginate($this->perPage);
        
        //$strs = implode(',', $tagIds);
        //$placeholder = '';
        
//        foreach ($tagIds as $key => $value) {
//           $placeholder .= ($key == 0) ? $value : ','.$value;
//        }
//        exit;
//        
//        $strs = "FIELD(id, $strs)";
//        echo $strs;
//        exit;
        
        //->orderByRaw("FIELD(id, $sortIDs)"
//        $tags = Tag::whereIn('id', $tagIds)->orderByRaw("FIELD(id, $placeholder)")->take(2)->get();
//        print_r($tags);
//        exit;

		$metaTitle = '';
        $metaDesc = '';
        $metaKeyword = '';
        
        $type = 'tag';
        
        $tag->timestamps = false;
        $tag->increment('view_count');

		return view('main.archive.index', ['userImgs'=>$userImgs, 'tag'=>$tag, 'metaTitle'=>$metaTitle, 'metaDesc'=>$metaDesc, 'metaKeyword'=>$metaKeyword, 'type'=>$type]);
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
