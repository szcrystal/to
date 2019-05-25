<?php
use App\Setting;
use App\Favorite;
use App\Good;
?>

<?php
//	$isCate = (isset($type) && $type == 'category') ? 1 : 0;
//	
//    $category = Category::find($item->cate_id);
    $link = url('/post/'. $userImg->id);
//    ///$linkName = isset($category->link_name) ? $category->link_name : $category->name;
   
//    if($isCate && isset($item->subcate_id)) {
//        $subCate = CategorySecond::find($item->subcate_id);
//        $cateLink = url('category/'. $category->slug . '/' . $subCate->slug);
//        $cateName = isset($subCate->link_name) ? $subCate->link_name : $subCate->name;
//    }
//    else {
//    	$cateLink = url('category/'. $category->slug);
//    	$cateName = isset($category->link_name) ? $category->link_name : $category->name;
//    }
//
//    $isSp = Ctm::isAgent('sp');
//    $isSale = Setting::get()->first()->is_sale;
//    
    $imgClass = '';
//    
//    //pot売り切れ判定
//    $potsArr = Ctm::isPotParentAndStock($item->id); //親ポットか、Stockあるか、その子ポットのObjsを取る
//    
//    $isStock = $potsArr['isPotParent'] ? $potsArr['isStock'] : ($item->stock ? 1 : 0); //pot親でない時は通常Itemの在庫を見る
   
?>




<div class="img-box">
    	<?php $imgClass = 'stock-zero'; ?>
    	<span>コメント</span>
    
    <a href="{{ $link }}">
    	<img src="{{ Storage::url($userImg->img_path) }}" alt="{{ $userImg->title }}" class="{{ $imgClass }}">
    </a>
</div>

<div class="meta">
    <h3><a href="{{ $link }}">
        {{-- Ctm::shortStr($userImg->title, $strNum) --}}
    </a></h3>


    <div class="tags">
        <?php $num = 2; ?>
        @include('main.shared.tag')
    </div>
            
    
    @if(! isset($isTop))
        <div class="price">
            <?php
                $favCount = Favorite::where('img_id', $userImg->id)->get()->count();
                $goodCount = Good::where('img_id', $userImg->id)->get()->count();
            ?>
            
            <span class="mr-2"><i class="fas fa-heart"></i> {{ $favCount }}</span>
            <span><i class="fas fa-thumbs-up"></i> {{ $goodCount }}</span>
        </div>
    @endif

                <?php
//                    if(Favorite::where(['user_id'=>Auth::id(), 'item_id'=>$item->id])->first()) {
//                        $on = ' d-none';
//                        $off = ' d-inline'; 
//                        $str = 'お気に入りの商品です';              
//                    }
//                    else {
//                        $on = ' d-inline';
//                        $off = ' d-none';
//                        $str = 'お気に入りに登録';
//                    }               
                ?>


    
</div>


