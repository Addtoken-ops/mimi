<?php
    if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    require_once dirname(__FILE__) . '/orange_secret.class.php';
    
    $act = addslashes($_GET['act']);
    $uid = (int)$_G['uid'];
    $page_limit = 10;
    $siteurl = $_G['siteurl'];
    $username = $_G['username'];
    $lang = lang('plugin/orange_secret');
    $orange_conf = $_G['cache']['plugin']['orange_secret'];
    $orange_conf['item_color'] = explode("\r\n",$orange_conf['item_color']);
    $is_manage = in_array($uid,explode("\r\n",$orange_conf['item_manage']));
    
    /*首页〉*/
    if( !$act ){
    	$condition = array('orderby'=>$orange_conf['item_sort']);
    	$page_count = ceil( C::t('#orange_secret#orange_secret_list')->get_secret_count(0) / $page_limit );
    	$page_list = C::t('#orange_secret#orange_secret_list')->get_secret_list( 0,$page_limit,$condition );
    	$praise_id = OrangeSecret::initial_data($page_list,'id','',1);
    	$praise_list = OrangeSecret::initial_data(C::t('#orange_secret#orange_secret_praise')->get_user_praise( $praise_id,$uid ),'lid','id',2);
        include template('orange_secret:'.$orange_conf['item_style'].'_index');
    }else if( $act == 'praise' ){
    	if( $_GET['formhash'] != FORMHASH ){OrangeSecret::output(-1);}
    	if( !$uid && !$orange_conf['item_notlogin'] ){OrangeSecret::output(0);}
    	$data = OrangeSecret::check_array($_POST,3);
    	$condition = array('lid'=>$data['lid'],'uid'=>$uid);
    	if( $data['handle_type'] == 'confirm' ){
    		C::t('#orange_secret#orange_secret_praise')->insert( $condition );
    	}else if( $data['handle_type'] == 'cancel' ){
    		C::t('#orange_secret#orange_secret_praise')->delete( $condition );
    	}
    }else if( $act == 'ajax_secret' ){
    	$condition = array('orderby'=>$orange_conf['item_sort']);
    	$start = intval($_POST['start']);
    	$page_list = C::t('#orange_secret#orange_secret_list')->get_secret_list( $start,$page_limit,$condition );
    	$praise_id = OrangeSecret::initial_data($page_list,'id','',1);
    	$praise_list = OrangeSecret::initial_data(C::t('#orange_secret#orange_secret_praise')->get_user_praise( $praise_id,$uid ),'lid','id',2);
    	include template('orange_secret:'.$orange_conf['item_style'].'_ajax');
    }
    
?>