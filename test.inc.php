<?php
    if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    
    require_once libfile('api/orange_magapp', 'plugin/orange_secret');
    $res = orange_magapp::sendAssistantMsg(array(
    	'user_id'=>64394,
    	'type'=>'singlepic',
    	'host'=>'http://m.yili6.com',
    	'secret'=>'AzZshKDWkpFpff4ACPNwWpGiGTQPirsh',
    	'assistant_secret'=>'68d7857095c32654496d7df41be2df65',
    	'content'=>array(
    		"tag"=> "notice",
		    "title"=> "title",
		    "link"=> "http://www.yili6.com/plugin.php?id=orange_secret",
		    "cover_url"=> "http://www.yili6.com/source/plugin/orange_secret/static/images/notice".rand(1,4).".jpg?3",
		    "extra_info"=>array(
	            array(
	            	"key"=> "title",
	            	"val"=> "cont"
	            )
		    )
    	)
    ));
    print_r($res);
?>