<?php
    if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    require_once dirname(__FILE__) . '/orange_secret.class.php';
    
    $act = addslashes($_GET['act']);
    $uid = $_G['uid'];
    $siteurl = $_G['siteurl'];
    $username = $_G['username'];
    $sitename = $_G['setting']['sitename'];
    $lang = lang('plugin/orange_secret');
    $orange_conf = $_G['cache']['plugin']['orange_secret'];
    $orange_conf['item_color'] = explode("\r\n",$orange_conf['item_color']);
    $orange_conf['item_tags'] = explode("\r\n",$orange_conf['item_tags']);
	
	
    if( !$act ){
    	include template('orange_secret:'.$orange_conf['item_style'].'_publish');
    }else if( $act == 'publish' ){
    	if( $_GET['formhash'] != FORMHASH ){OrangeSecret::output(-1);}
    	$orange_conf['item_keywords'] = explode("\r\n",$orange_conf['item_keywords']);
    	if( !$uid && !$orange_conf['item_notlogin'] ){OrangeSecret::output(3);}
    	$data = OrangeSecret::check_array($_POST,3);
    	$data['uid'] = $uid;
    	$data['hid'] = rand(1,11);
    	$data['is_hide'] = $uid?0:1;
    	$data['username'] = $username;
    	$data['add_time'] = $_G['timestamp'];
    	$data['last_time'] = $_G['timestamp'];
    	$data['content'] = OrangeSecret::replace_keywords($data['content'],$orange_conf['item_keywords']);
    	$result = C::t('#orange_secret#orange_secret_list')->insert( $data );
    	if( $result ){
    		OrangeSecret::output(1);
    	}else{
    		OrangeSecret::output(2);
    	}
    }
    
?>