var reg = /^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/;
function get_rgb( colors ){
    var sColor = colors.toLowerCase();
    if(sColor && reg.test(sColor)){
        if(sColor.length === 4){
            var sColorNew = "#";
            for(var i=1; i<4; i+=1){
                sColorNew += sColor.slice(i,i+1).concat(sColor.slice(i,i+1));
            }
            sColor = sColorNew;
        }
        //处理六位的颜色值
        var sColorChange = [];
        for(var i=1; i<7; i+=2){
            sColorChange.push(parseInt("0x"+sColor.slice(i,i+2)));
        }
        return sColorChange.join(",");
    }else{
        return sColor;
    }
}
$(document).on('click','.open_page',function (){
    open_page(siteurl+$(this).attr('href'));
    return false;
});
function open_page( open_url,closePage ){
    if( !open_url )return false;
	if( closePage ){
		is_xiaoyun && sq.closeActivity();
		is_magappx && mag.closeWin();
		is_qianfan && QFH5.close();
	}
    if( is_xiaoyun ){
        sq.urlRequest( open_url );
    }else if( is_magappx ){
    	mag.newWin( open_url );
    }else if( is_qianfan ){
    	QFH5.jumpNewWebview( open_url );
    }else{
        window.location.href = open_url;
    }
    return true;
}
function load_data( item_main,iMaxPage,datas,data_url,doc_main,before,after ){
    var page_main = doc_main || {doc_main:document,body_main:document};
    var view_height = $(window).height();
    var iNowPage = 1;
    var onOff = true;
    var time = null;
    var loading_item = null;

    $(document).bind('touchmove',move);
    $(window).bind('resize',function (){
        view_height = $(window).height();
    });

    function move(ev){
        var ev = ev || window.event;
        var aTouch = ev.changedTouches;
        var scr_top = $(page_main.doc_main).scrollTop();

        if( onOff && iNowPage < iMaxPage && scr_top + view_height > $(page_main.body_main).height() - 50){
            ajax_post();
            onOff = false;
            before && before();
        }else{
            var prevScrollTop = scr_top;
            clearInterval(time);
            time = setInterval(function (){
                var thisScrollTop = $(page_main.doc_main).scrollTop();
                if(thisScrollTop==prevScrollTop){
                    clearInterval(time);
                }
                prevScrollTop = $(page_main.doc_main).scrollTop();
            },200);
        }
    }


    function ajax_post(){
        if(!data_url)return;
        loading_item = layer.open({
            content:'\u6b63\u5728\u52a0\u8f7d\u002e\u002e\u002e',
            skin: 'msg',
        });
        $.ajax({
            type:'post',
            url:data_url,
            data:$.extend(datas,{start:iNowPage*10}),
            success:function ( data ){
                layer.close(loading_item);
                if( !data ){
                    onOff = false;
                }else{
                    iNowPage++;
                    onOff = true;
                    after && after();
                    $(item_main).append(data);
                }
            }
        });
    }
}
function get_rand( imin,imax ){
	return Math.round( Math.random()*(imax-imin)+imin );
}
function onLogin(){
	layer.open({
	    content:'\u672a\u767b\u5f55\uff0c\u6b63\u5728\u8df3\u8f6c\u767b\u9646\u002e\u002e\u002e',
	    skin: 'msg',
	    time:2,
	    end:function (){
	    	if( is_xiaoyun ){
	    		sq.login(function(userInfo){
	    			is_login = true;
	    			window.location.reload();
	    		});
	    	}else if( is_magappx ){
	    		mag.toLogin(function (){
	    			is_login = true;
	    			window.location.reload();
	    		});
	    	}else if( is_qianfan ){
	    		QFH5.jumpLogin(function(state,data){
					if(state==1){
						is_login = true;
	    				window.location.reload();
					}
				});
	    	}else{
	    		window.location.href = 'member.php?mod=logging&action=login';
	    	}
		}
	});
}
function getLocation( position_data ){
 	if( is_xiaoyun ){
        sq.getLocation(function(location){
        	position_data.address = location.address;
	    });
	}else if( is_magappx ){
		mag.getLocation(function(location){
			position_data.address = location.location;
 		});
	}else if( is_qianfan ){
		QFH5.getLocation(function(state,data){
			if(state==1){
				position_data.address = data.address;
			}
		})
	}else if( navigator.geolocation ){
		navigator.geolocation.getCurrentPosition(function (p){
            var lat = decimal_cut(p.coords.latitude);
            var lng = decimal_cut(p.coords.longitude);
			var geocoder = new AMap.Geocoder({
	            radius: 1000,
	            extensions: "all"
       		});
	        geocoder.getAddress([lng,lat], function(status, result) {
	            if (status === 'complete' && result.info === 'OK') {
	                position_data.address = result.regeocode.formattedAddress;
	            }
	        });
        });
	}
	return position_data;
}
function decimal_cut( number,length ){
    var number = number+'';
    var length = length?length:6;
    return number.substr(0,number.indexOf('.')+(length+1));
}
