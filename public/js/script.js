(function($) {

var exe = (function() {

	return {
    
		opts: {
            crtClass: 'crnt',
            btnID: '.top_btn',
            all: 'html, body',
            t: 0,
            
            animEnd: 'webkitAnimationEnd MSAnimationEnd oanimationend animationend', //mozAnimationEnd
            transitEnd: 'webkitTransitionEnd MSTransitionEnd otransitionend transitionend', //mozTransitionEnd 
        },
        
        scrollFunc: function() {
            var t = this,
                tb = $(t.opts.btnID);
            
            tb.css('display','none').on('click', function() {
                $(t.opts.all).animate({ scrollTop:0 }, 1200, 'easeOutExpo');
            });

            $(document).scroll(function(){

                if($(this).scrollTop() < 200)
                    tb.fadeOut(200);
                else 
                    tb.fadeIn(300);
            });
            
        },
        
        
        isAgent: function(user) {
            if( navigator.userAgent.indexOf(user) > 0 ) return true;
        },
        
        isLocal: function() {
        	if( location.port == 8006 ) return true;
        },
        
        isSpTab: function(arg) {

        	var spArr = ['iPhone','iPod','Mobile ','Mobile;','Windows Phone','IEMobile'];
            var tabArr = ['iPad','Kindle','Sony Tablet','Nexus 7','Android Tablet'];
            var arr = [];
            
            if(arg == 'sp')
            	arr = spArr;
            
            else if(arg == 'tab')
            	arr = tabArr;
            
            else
            	arr = spArr.concat(tabArr);
            
        	
            var th = this;
            var bool = false;
            
            arr.forEach(function(e, i, a) {
            	if(th.isAgent(e)) {
                	bool = true;
                    return; //Exit
                }
            });
            
            return bool;
        },
        
        
        
        
        put: function(tag, argText) {
            $(tag).text(argText);
            console.log("CheckText is『 %s 』" , argText);
        },
        
        
        
        addClass: function() {
        	//$('.add-item').find('.item-panel').eq(0).addClass('first-panel');
            $('.item-panel').eq(0).css({border:'2px solid green'});
        },
        
        nl2br: function(str) {
            str = str.replace(/\r\n/g, "<br>");
            str = str.replace(/(\n|\r)/g, "<br>");
            return str;
        },
        
        searchSlide: function() {
        	
            var th = this;
            
        	var $input = $('.s-form-wrap');
            var $nav = $('.nav-sp-wrap');
            
            //var $width = this.isSpTab('sp') ? '65%' : '13em';
            var speed = 150;
            var ease = 'linear';
           
            $('.s-tgl').on('click', function(){
                if($input.is(':hidden')) {
                	
                    if($nav.is(':visible')) {
                    	$nav.slideUp(speed, function(){
                        	$(this).queue([]).stop();
                    		$(this).css({ height:0 });
                        });
                        
                        $('html,body').css({position:'static'}).scrollTop(th.opts.t);
                    }
                    
                    $input.slideDown(speed, ease, function(){
                        $(this).queue([]).stop();
                    });
                }
                else {
                    $input.slideUp(speed, ease, function(){
                        $(this).queue([]).stop(); 
                    });
                
                }
            });

        },
        
        toggleSp: function() {
           
//        	$('.head-navi .fa-search').on('click', function(){
//            	$('.s-form input').slideToggle(150);
//            });
           
            var th = this;
            
            var $navWrap = $('.nav-sp-wrap');
            var $nav = $('.nav-sp');
            var $sForm = $('.s-form-wrap');
            var $hasChild = $('.has-child');
            
            var speed = 250;
            var ease = 'linear';
            
            
            $('.nav-tgl').on('click', function() {
            
                if($navWrap.is(':hidden')) {
                	th.opts.t = $(window).scrollTop();
                    //$('html').css({overflow:'hidden', height:'100%'});
//                    $('body').on('touchmove.noScroll', function(e){
//                    	e.preventDefault();
//                    });
					
//                    $('.sp-fix-wrap').css({
//                    	position: 'fixed',
//                        zIndex: -1,
//                        width: '100%',
//                        height: '100%', 
//                    });
                    
                	if($sForm.is(':visible')) {
                    	$sForm.slideUp(100);
                    }
                    
                	//$navWrap.slideDown(speed);
                    
                    $navWrap.css({ height:$(window).height() }).slideDown(speed, ease, function(){
                    	$(this).queue([]).stop();
                    });
                    $('html,body').css({position:'fixed', top:-th.opts.t});
                }
                else {
                    //$('body').off('.noScroll');
                    
//                    $('.sp-fix-wrap').css({
//                    	position: 'static',
//                        zIndex: 1,
//                        width: 'auto',
//                        height: 'auto', 
//                    });

                    
                    $navWrap.slideUp(speed, ease, function(){
                    	$(this).queue([]).stop();
                    	$(this).css({ height:0 });
                    });
                    
                    //$('body').css({overflowY:'visible'});
                    $('html,body').css({position:'static'}).scrollTop(th.opts.t); 
                }
 
            });
            
            $hasChild.on('click', function(){
            	$ul = $(this).find('ul');
            	
                $hasChild.find('ul:visible').slideUp(speed);
                
                if($ul.is(':hidden')) {
                    $ul.slideDown(speed);
                }
                else {
                    $ul.slideUp(speed);
                }
                
            });
            
            
            /* ORG navi ===========================
            //$('.fade-black').css({height:'100%'})
            
            $('.nav-tgl').on('click', function(){
            	var $leftbar = $('.main-navigation');
                
                var h = $(window).height();
                //h = h-60;
                var speed = 50;
                
                $leftbar.find('.navi-body').css({height:h});

            	if($leftbar.is(':visible')) {
                 
                 	$('.fade-black').fadeOut(30, function() {
                        $leftbar.animate({left:'-100px'}, speed, ease, function(){
                            $(this).queue([]).stop().hide(0);
                            
                            $('html,body').css({position:'static'}).scrollTop(t);
                        });
                    });
                }
                else {
                	t = $(window).scrollTop();

                    $('.fade-black').fadeIn(10, ease, function(){
                        //$leftbar.fadeIn(10, ease, function(){
                    		$leftbar.show(0).animate({left:0}, speed, ease, function(){
                            	$(this).queue([]).stop();
                                $('html, body').css({position:'fixed', top:-t}); //overflow:'hidden',
                            });
                                
                        //});
    
                    });
                }
                //$('.navbar-brand').text(t);
            });
            */

            
        },
        
        //郵便番号のセット
        postNumSet: function() {
        	$('#zipcode').jpostal({
                postcode : [
                    '#zipcode'
                ],
                address : {
                    '#pref':'%3',
                    '#address':'%4%5'
                }
            });
            
            $('#zipcode_2').jpostal({
                postcode : [
                    '#zipcode_2'
                ],
                address : {
                    '#pref_2':'%3',
                    '#address_2':'%4%5'
                }
            });
            
        },
        
        
        dropDown: function() {
        	var $mainNav = $('.main-navi > ul > li'); //this.isSpTab('sp') ? $('.panel-body > ul > li') :
            
        	//var len = $('.state-nav li').length;
            var num = 0;
           
            var speed = 350;
           	var easing = 'easeInOutExpo';
           
           	var hideSpeed = this.isSpTab('sp') ? 150 : 100;
            //console.log(len);
           
           	//$('.menu-dropdown').eq(1).slideToggle(200);
            
            $mainNav.on('click', function(e){
            	//console.log('bbb');
				
                var $clickThis = $(this);
                var $dropMenu = $('.drops');
                
                var n = $clickThis.index();
                
                $(e.target).addClass('nav-active');
                
                if($dropMenu.eq(n).is(':visible')) {
                	
                    if(! $(e.target).hasClass('drops') ) {
                        $clickThis.removeClass('nav-active');
                        
                        $dropMenu.fadeOut(speed, easing, function() {
                            $(this).queue([]).stop();
                        });
                    }
                }
                else {
                	//console.log('ccc');

                    $dropMenu.fadeOut(hideSpeed, easing, function(){
                        $mainNav.removeClass('nav-active');
                        $clickThis.addClass('nav-active');
                        
                        $clickThis.children('.drops').fadeIn(speed, easing, function() {
                            $(this).queue([]).stop();
                        });
                    
                    });
                    
                }
                
                //return false;

            });
            
            if(! this.isSpTab('sp')) {
                $('body').on('click', function(e){
                    var $dropMenu = $('.drops');
                    
                    if( ! $(e.target).hasClass('drops') ) {
                        
                        //console.log("aaa");
                        
                        if($dropMenu.is(':visible')) {
                            
                            $('.main-navi li').removeClass('nav-active');
                            
                            $dropMenu.fadeOut(hideSpeed, easing, function() {
                                $(this).queue([]).stop();
                            });
                        }
                    }
                });
            }

            
            /*
            $mainNav.on({
            	'mouseover': function(e){
				
                    var $clickThis = $(this);
                    var $dropMenu = $('.drops');
                    
                    var n = $(this).index();
                    
                    $(e.target).addClass('nav-active');
                    
                    if($dropMenu.eq(n).is(':visible')) {
                        
                        $clickThis.removeClass('nav-active');
                        
                        $dropMenu.fadeOut(speed, easing, function() {
                            $(this).queue([]).stop();
                        });
                    }
                    else {
                    
                        $dropMenu.fadeOut(hideSpeed, function(){
                            $mainNav.removeClass('nav-active');
                            $clickThis.addClass('nav-active');
                            
                            $clickThis.children('.drops').fadeIn(speed, easing, function() {
                                $(this).queue([]).stop();
                            });
                        
                        });
                    }
                },
                
                'mouseout': function(e){
				
                    var $clickThis = $(this);
                    var $dropMenu = $('.drops');
                    
                    var n = $(this).index();
                    
                    $(e.target).addClass('nav-active');
                    
                    if($dropMenu.eq(n).is(':visible')) {
                        
                        $clickThis.removeClass('nav-active');
                        
                        $dropMenu.fadeOut(speed, easing, function() {
                            $(this).queue([]).stop();
                        });
                    }
                    else {
                    
                        $dropMenu.fadeOut(hideSpeed, function(){
                            $mainNav.removeClass('nav-active');
                            $clickThis.addClass('nav-active');
                            
                            $clickThis.children('.drops').fadeIn(speed, easing, function() {
                                $(this).queue([]).stop();
                            });
                        
                        });
                    }
                }
                
            });
            */
            
  
        },
        
        
        eventItem: function() {
           
            //Thumbnail Upload
            $('.thumb-file').on('click', function(){
            	var $th = $(this);
                $th.on('change', function(e){
                	var file = e.target.files[0],
                    reader = new FileReader(),
                    $preview = $(this).parents('.thumb-wrap').find('.thumb-prev');
                    //t = this;

                    // 画像ファイル以外の場合は何もしない
                    if(file.type.indexOf("image") < 0){
                      return false;
                    }

                    // ファイル読み込みが完了した際のイベント登録
                    reader.onload = (function(file) {
                      return function(e) {
                        //既存のプレビューを削除
                        $preview.empty();
                        // .prevewの領域の中にロードした画像を表示するimageタグを追加
                        $preview.append($('<img>').attr({
                                  src: e.target.result,
                                  width: "100%",
                                  //class: "preview",
                                  title: file.name
                        }));
                        //console.log(file.name);

                    };
                })(file);

                reader.readAsDataURL(file);
                });
            	
            });
        
        },
        
        autoComplete: function() {
           
            var data = [];
           
           	function addTagOnArea(target, text, groupId, val=0) {
           		var $tagArea = $(target).siblings('.tag-area');
           		var bool = true;
           
                $tagArea.find('.text-danger').remove();
           
                $tagArea.find('span').each(function(){
                	var preTag = $(this).text();

                	if(text == preTag) {
                    	bool = false;
                    }
                });
           
           		if(bool) {
                	//$tagArea.append('<span data-text="'+text+'" data-group="'+groupId+'" data-value="'+val+'"><em>' + text + '</em><i class="fa fa-times del-tag" aria-hidden="true"></i></span>');
                    $tagArea.append('<span><em>' + text + '</em><i class="fa fa-times del-tag" aria-hidden="true"></i></span>');
                       //$tagArea.append('<input type="hidden" name="'+groupId+'[]" value="'+text+'">');
                    $tagArea.append('<input type="hidden" name="tags[]" value="'+text+'">');
                }
                else {
           			$tagArea.prepend('<p class="text-danger"><i class="fa fa-exclamation" aria-hidden="true"></i> 既に追加されているタグです</p>');
                }
           
                return bool;
            }
           
            $(document).delegate('.del-tag', 'click', function(e){
                var $span = $(e.target).parent('span');
//                if($span.data('value'))
//                	data[$span.data('group')].splice($span.data('value'), 0, $span.data('text')); //or push
                
                $span.next('input').remove(); //先にinputをremove
                $span.fadeOut(150).remove();
            });
           
           
            $('.tag-control').each(function(){
            	var group = $(this).attr('id');
                var tagList = $(this).siblings('span').text();
                //$('.panel-heading').text(tagList);

                data[group] = tagList.split(',');
                var $tagInput = $('#' + group);
                
                $tagInput.autocomplete({
                    source: data[group],
                    autoFocus: true,
                    delay: 50,
                    minLength: 1,
                    
                    select: function(e, ui){
                    	var $num = data[group].indexOf(ui.item.value); //配列内の位置
                        var bool = addTagOnArea(e.target, ui.item.value, group, $num);
                        if(bool) {
//                        	if($num != -1)
//                            	data[group].splice($num, 1); //リストから削除
	                        
                            ui.item.value = '';
                        }
                    },
                    
                    response: function(e, ui){
                    	//$('.panel-heading').text(ui.content['label']);
                    	//if(ui.content == '') {
                        	$(e.target).siblings('.add-btn:hidden').fadeIn(50).css({display:'inline-block'});
                            //console.log('response');
                        //}
                        //else {
                        //	$(e.target).siblings('.add-btn').fadeOut(100);
                        //}
                        
                        //$(this).autocomplete('widget')
                    },
                    close: function(e, ui){
                    	/*
                    	if($(e.target).val().length > 1) {
                        	$(e.target).siblings('.add-btn').fadeIn(100).css({display:'inline-block'});
                        }
                        */
                    },
                    focus: function(e, ui){
						//console.log(ui.item);
                    },
                    search: function(e, ui){
                    },
                    change: function(e, ui) {
                    	console.log(ui);
                    },
                    
            	}); //autocomplete

                
                $tagInput.next('.add-btn').on('click keydown', function(event){
                	//console.log(event.which);
                	if (event.which == 1 || event.which == 13) {
                        var texts = $('#' + group).val();
                        
                        var bool = addTagOnArea('#' + group, texts, group);
                        if(bool) {
                        	$tagInput.val('');
                        	$(this).fadeOut(100);
                        }
                    }
                });
                
                $tagInput.on('keydown keyup', function(event){ //or keypress
                	if(event.which == 13) {//40
                    	if(event.type=='keydown') { // && $('.ui-menu').is(':hidden')
                        	var texts = $(this).val();
                            if(texts != '') {
                        		if(addTagOnArea(this, texts, group)) { //tag追加
                             		$(this).val('');
                                    //$(this).next('.add-btn:visible').fadeOut(50);
                                }
                             
                                $('.ui-autocomplete').hide();                             
                            }
                        }
                    	event.preventDefault();
                    }
                    
                    //if($(this).val().length < 2) { //event.which == 8 &&
                    if(event.type=='keyup' && $(this).val().length < 1) {
                		$(this).next('.add-btn').fadeOut(10);
                        $(this).siblings('.tag-area').find('.text-danger').remove();
                	}
                    
                    if(event.which != 13 && event.which != 8 && $(this).val().length > 0) {
                    	$(this).siblings('.add-btn:hidden').fadeIn(50).css({display:'inline-block'});
                    }
                });
            
            }); //each function
           
        },
        
        outReceive: function() {
        	var $destination = $('input[name="destination"]');
         	var $em = $('.receiver').find('em');
            var $rWrap = $('.receiver-wrap');
             
              if($destination.is(':checked')) {
              	$rWrap.show();
              	$em.show();
              }
              else {
              	$rWrap.hide();
              	$em.hide();
              }

            $destination.on('click', function(){
                if($(this).is(':checked')) { 
                    $em.fadeIn(30, function(){
                    	$rWrap.slideDown(300);
                    });
                }
                else {
                	
                    $rWrap.slideUp(300, function(){
                    	$em.fadeOut(30);
                    });
                    
                    $('.receiver-error:visible').fadeOut(30).siblings().removeClass('is-invalid');
                    //$('.receiver-error:visible');
                    
                }
            });
        },
        
        addFavorite: function() {
        	var $fav= $('.fav');
            var $favOn = $('.fav-on');
            var $favOff = $('.fav-off'); 
          
//              var url = $(this).parent('div').find('.link-url').val(); //input type=text
//            var $frame = $(this).parent('div').find('.link-frame');
//            //console.log(url);     
         
             $fav.on('click', function(e){
             	
                var $th = $(this);
                var $loader = $('.favorite .loader');
                
                var _imgId = $(this).data('id');
                var _type = $(this).data('type');
                var _tokenVal = $('input[name=_token]').val();
                var _isOn = 0;
                
                $th.removeClass('d-inline').fadeOut(160, function(){
                    //loader表示
//                	$loader.fadeIn(30);
                    //$(this).queue([]).stop();
                    
                    //});
                    
                    if($th.hasClass('fav-on')) { //登録削除の時
                        _isOn = 1;
                        $sibFav = $th.siblings('.fav-off');
                    }
                    else { //登録時
                        $sibFav = $th.siblings('.fav-on');
                    }


                    //controllerでajax処理する場合、_tokenも送る必要がある
                    $.ajax({
                        url: '/post/favgood-script',
                        type: "POST",
                        cache: false,
                        data: {
                            _token: _tokenVal,
                            imgId: _imgId,
                            type: _type,
                            isOn: _isOn,
                        },
                        //dataType: "json",
                        success: function(resData){
                            
                            var str = resData.str;
                            //console.log(str);
                            
                            if(_isOn) { //お気に入り登録削除の時
                                //$th -> hasClass:.favOn
                                //$th.removeClass('d-inline').fadeOut(100, function(){
                                    //$loader.fadeOut(30, function(){
                                        $sibFav.removeClass('d-none').fadeIn(20);
                                    //});
                                     
                                    //$favOff.removeClass('d-none').fadeIn(50); 
                                //});
                            } 
                            else { //お気に入り登録の時
                                //$th -> hasClass:.favOff
                                //$th.removeClass('d-inline').fadeOut(100, function(){
                                    //$loader.fadeOut(30, function(){
                                        $sibFav.removeClass('d-none').fadeIn(20);
                                    //});
                                    //$favOn.removeClass('d-none').fadeIn(50);
                                //});
                            }
                            
                            //text表示 strはphpから取得
                            $th.siblings('small').text(str);
                            
                            //exit();
                            
    //                        $select2.empty().append(
    //                            $('<option>'+ '選択して下さい' + '</option>').attr({
    //                                  disabled: 1,
    //                                  selected: 1,
    //                            })
    //                        );
    //                        
    //                        $.each(selectArr, function(index, val){
    //                            console.log(Object.keys(val));
    //                            
    //                            $select2.append(
    //                                $('<option>' + Object.values(val) + '</option>').attr({
    //                                  value: Object.keys(val),
    //                                  
    //                                })
    //                            );
    //                        }); //each
                            
                            //$frame.html(resData).slideDown(100);
                        },
                        error: function(xhr, ts, err){
                            //resp(['']);
                        }
                    }); //ajax
            	
                }); //fadeOut
                
            });// on
        },
        
        slideDeliFee: function() {
        	$('.slideDeli').on('click', function(){
            	$target = $(this).next('.table-deli');
                
                $target.slideToggle(250);
            });
        },
        
        faqCate: function() {
        	
            var fixHeight = $('.fixed-top').height();
            
            
        	$('.faq-cate li').on('click', function(e){
            	$index = $('.faq-cate li').index(this);
                
                fixHeight = fixHeight + 20;

                $posi = $('.faq section').eq($index).offset().top - fixHeight;
                
                $("html,body").animate({scrollTop:$posi});
                                                
            });
               
        },
        
        potSetSelect: function() {
        	
            var $btn = $('.btn[type="submit"]');
            
        	$('.potSetSelect').on('change', function(e) {
            	
                var arr = [];
                var let = true; //true:disabledになる false:disabled解除  0/1では不可
                
            	$('.potSetSelect').each(function() {
                	var value = $(this).find('option:selected').val();
                    arr.push(value);
                    //console.log(arr);
                });
                
                
                $.each(arr, function(index, val){
                	if(val > 0) { //1以上の個数がある時
                    	let = false;
                        return false; //ループから出る
                    }
                });
                
                
                $btn.attr('disabled', let);

            });
        },
        
        slidePayMethodChild: function() {
        	var $pmRadio = $('.payMethodRadio');
            var $useCardRadio = $('.useCardRadio');
            var $editCardRadio = $('.editCardRadio');
            
            var $wrapPmc = $('.wrap-pmc');
            var $wrapAllCard = $('.wrap-all-card');
            var $wrapNewCard = $('.wrap-new-card');
            var $wrapExpire = $editCardRadio.parent('label').next('.wrap-expire');
            
            //クレカを選択した時
            if($('.payMethodRadio:checked').val() == 1) {
            	$wrapAllCard.show();
                //console.log($('.payMethodRadio:checked').val() + "a");
            }
            
            $pmRadio.on('change', function(e){
            	if($(this).val() == 1) {
                	$wrapAllCard.slideDown(100); 
                }
                else {
                	$wrapAllCard.slideUp(100);
                }
            	
            });
            
            //クレカ内にあるRadioCheck（登録カード or 新規カード）
            if($('.useCardRadio:checked').val() == 99 || ! $useCardRadio.length) { //valが99か、radioCheckがない時（カード登録なしや非会員登録の時など）
            	$wrapNewCard.show();
            }
            
            $useCardRadio.on('change', function(){
            	if($(this).val() == 99) {
                	$wrapNewCard.slideDown(100); 
                }
                else {
                	$wrapNewCard.slideUp(100);
                }
            
            });
            
            //console.log($('.payMethodRadio:checked').val());
            
            
            //マイページ：有効期限更新Radio                        
            $('.editCardRadio:checked').each(function(e){
                if($(this).val() == 1) {
	                $(this).parent('label').next('.wrap-expire').show();
    	        }
            });
            
            $editCardRadio.on('change', function(e){
            	var $wrapExpire = $(this).parent('label').siblings('.wrap-expire');
                
            	if($(this).val() == 1) {
                	$wrapExpire.slideDown(100); 
                }
                else {
                	$wrapExpire.slideUp(100);
                }
            	
            });
            
            
            //Contact
            var $askTelWrap = $('.ask-tel-wrap');
            var v = $('.isAskType:checked').val();
            
            if(v == 1) {
                $askTelWrap.show();
            }
            else if(v == 2) {
                $askTelWrap.hide();
            }
            else {
            	$askTelWrap.hide();
            }
            
            
            $('.isAskType').on('change', function(e){                
                var vv = $(this).val();
                
            	if(vv == 1) {
                	$askTelWrap.slideDown(50); 
                }
                else if(vv == 2) {
                	$askTelWrap.slideUp(50);
                }
            	
            });
            
            
            //ネットバンク銀行選択
//            if($('.payMethodRadio:checked').val() == 3) {
//            	$wrapPmc.show();
//            }
//            
//            $pmRadio.on('change', function(e){
//            	if($(this).val() == 3) {
//                	$wrapPmc.slideDown(100); 
//                }
//                else {
//                	$wrapPmc.slideUp(100);
//                }
//            	
//            });
            
            //console.log($pmRadio.find(':checked').val());
        },
        
        
        toggleSideMenu: function(){
        	$tgl = $('.more-tgl');
            speed = 150;
            
            $tgl.on('click', function(e){
            	$list = $(this).next('.more-list');
                
                $list.slideToggle(speed);
            	
//                if($list.is(':hidden')) {
//                	$list.slideDown(speed);
//                }
//                else {
//                	$list.slideUp(speed);
//                }
            });
            
        },
        
        //スマホ時 Single 詳細などのaccordion
        accordionMoveUp: function(){
            //var $ac = $('.single #accordion');            
            //var h = $('.upper-wrap').height();
            
            var fixH = $('.fixed-top').height() + 10;
            //var top = $ac.offset().top - fixH;            
            
            $('.single .card-header').on('click', function(){
            	var t = $(this).parents('#accordion').offset().top;
                t -= fixH;
                            
                //$('html, body').scrollTop(t);
                $('html, body').animate({scrollTop:t}, 50, 'linear', function(){
                	$(this).queue([]).stop();
                });
            });

        },
        
        //TopSliderの枠調整
        setSliderFrame: function() {
        	function set() {
                var $thisItem = $('.this-item');
                var $current = $('.slider-nav.slick-initialized .slick-current');
                
                var $prevNext = $('.slick-prev, .slick-next');
                
                //var pad = parseInt($thisItem.css('borderWidth')); //pxが付くので firefoxで効かないのでborder-top-widthとする必要がある
                var pad = parseInt($thisItem.css('borderTopWidth'));

                var w = $current.width() + pad;
                var h = $current.height() + (pad*2);
                
                var posiL = ($('.slider-wrap').width() - w ) / 2;
                
                $thisItem.css({width:w, height:h, left:posiL});
                
                $prevNext.css({top:h/2 - ($prevNext.height()/2)});
                
                //$('h2').text(pad + '/');
            }
            
            //set(); //docu.ready時は不要 下記setPositionでsetされる
            
            $('.slider-nav').on('setPosition', function(){
            //$(window).on('resize', function() {
                //if($(window).width() > 1200) {
            		set();
                //}
            });

            
        },
        
        
        getCardToken: function() {
        	
            $('#card-submit').on('click', function() {
            	
                $(this).addClass('invisible');
                $('.loader').fadeIn(100);
                //exit();
                
                //本番 or Not
                var isProduct = $(this).data('product');
            
            	//必要要素の値取得
                var cardno, expire, securitycode, holdername;
                
                var cardno = $("#cardno").val();
                var expire = $("#expire_year").val() + $("#expire_month").val();
                var securitycode = $("#securitycode").val();
                var holdername = $("#holdername").val();
                var tokennumber = $("#tokennumber").val();
                
                //shopId
                var shopId = isProduct ? '9200000204151' : 'tshop00036826';
                

                Multipayment.init(shopId); //ここを変えるか暗証番号の桁数を変えるとエラーにすることが出来る
                //Multipayment.init("tshop000");
                    
                Multipayment.getToken({ 
                    cardno : cardno, 
                    expire : expire,
                    securitycode : securitycode, 
                    holdername : holdername, 
                    tokennumber : tokennumber

                }, function(response) { //callback function purchaseDoの部分
                    if (response.resultCode != "000") {
                        //window.alert("購入処理中にエラーが発生しました");
                        location.href = "/shop/form?carderr=" + response.resultCode;
                    }
                    else {
                        // カード情報は念のため値を除去
                        $("#cardno").val('');
                        $("#expire_year").val('');
                        $("#expire_month").val('');
                        $("#securitycode").val('');
                        $("#tokennumber").val('');
                        
                        // 予め購入フォームに用意した token フィールドに、値を設定 
                        //発行されたトークンは、有効期限が経過するか、一度 API で利用されると、無効となります。 
                        //複数の API でトークンを利用される場合は、tokenNumber にてトークンを複数発行してください。
                        $("#token").val(response.tokenObject.token);                

                        // スクリプトからフォームをsubmit
                        $("#purchaseForm").submit();
                    }
                }

                ); //Multipayment.getToken
                
            }); //card-submit
            
            
            
            $('#regist-submit, #exist-submit').on('click', function(){
            	$(this).addClass('invisible');
                $('.loader').show();
                
                //return false;
            });
                
        },
        
        getWH: function() {
        	$target = $('.top-first .img-box');
        	var w = $target.width();
            var h = $target.height();
            
            //$('h2').text(w +'/'+ h);
            //console.log(w +'/'+ h);
            
                               
        	//アンカーリンクのfix headerのずれを直す
            var fixH = $('.fixed-top').height();
           	var url = $(location).attr('href');
            
            if(url.indexOf("#") != -1) {
                var anchor = url.split("#");
                var target = $('#' + anchor[anchor.length - 1]);
                
                fixH = fixH + 10;
                
                if(target.length){
                    //var pos = Math.floor(target.offset().top) - fixHeight;
                    var pos = target.offset().top - fixH;
                    $("html, body").scrollTop(pos);
                    //$("html, body").animate({scrollTop:pos}, 500);
                }
            }
            
                
        },
        
        
        
    } //return

})();


$(function(e){ //ready
    
    exe.autoComplete();
    exe.getWH();
    
    if(! exe.isSpTab('sp')) {
    	exe.scrollFunc();
    }
    else {
    	exe.toggleSp();
    }
    
    exe.searchSlide();

    //exe.dropDown();
    exe.eventItem();
    
    
    exe.outReceive();
    exe.addFavorite();
  
  	exe.postNumSet();
    
    exe.slideDeliFee();
    
    exe.faqCate();
    
    exe.potSetSelect();
    
    exe.slidePayMethodChild();
    
    exe.toggleSideMenu();
    
    exe.accordionMoveUp();
    exe.setSliderFrame();
    
    exe.getCardToken();
});


/* Easing */
$.easing['jswing'] = $.easing['swing'];

$.extend( $.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		//alert($.easing.default);
		return $.easing[$.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - $.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return $.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return $.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});



})(jQuery);
