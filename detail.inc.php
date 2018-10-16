<?php
    if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    require_once dirname(__FILE__) . '/orange_secret.class.php';
    
    $act = addslashes($_GET['act']);
    $uid = $_G['uid'];
    $page_limit = 10;
    $siteurl = $_G['siteurl'];
    $username = $_G['username'];
    $sitename = $_G['setting']['sitename'];
    $lang = lang('plugin/orange_secret');
    $orange_conf = $_G['cache']['plugin']['orange_secret'];
    $orange_conf['item_color'] = explode("\r\n",$orange_conf['item_color']);
    $is_manage = in_array($uid,array_filter(explode("\r\n",$orange_conf['item_manage'])));
	
    if( !$act ){
    	$lid = intval($_GET['lid']);
    	$condition = array('lid'=>$lid,'orderby'=>1);
    	$secret = C::t('#orange_secret#orange_secret_list')->get_secret_first( $lid );
    	if( !$secret ){dheader('location:plugin.php?id=orange_secret');}
    	$is_praise = C::t('#orange_secret#orange_secret_praise')->get_user_praise( $lid,$uid );
    	$comment_list = C::t('#orange_secret#orange_secret_comment')->get_comment_list( 0,$page_limit,$condition );
    	$comment_count = ceil( C::t('#orange_secret#orange_secret_comment')->get_comment_count( $condition ) / $page_limit );
    	include template('orange_secret:'.$orange_conf['item_style'].'_detail');
    }else if( $act == 'comment' ){
    	if( $_GET['formhash'] != FORMHASH ){OrangeSecret::output(-1);}
    	$orange_conf['item_keywords'] = explode("\r\n",$orange_conf['item_keywords']);
    	if( !$uid && !$orange_conf['item_notlogin'] ){OrangeSecret::output(0);}
    	$data = OrangeSecret::check_array($_POST,3);
    	$data['uid'] = $uid;
    	$data['username'] = $username;
    	$data['add_time'] = $_G['timestamp'];
    	$data['content'] = OrangeSecret::replace_keywords($data['content'],$orange_conf['item_keywords']);
    	
    	$secret = C::t('#orange_secret#orange_secret_list')->get_secret_first( $data['lid'] );
    	
    	require_once libfile('api/orange_magapp', 'plugin/orange_secret');
	    $res = orange_magapp::sendAssistantMsg(array(
	    	'type'=> 'singlepic',
	    	'user_id'=> $secret['uid'],
	    	'host'=> $orange_conf['site_host'],
	    	'secret'=> $orange_conf['site_secret'],
	    	'assistant_secret'=> $orange_conf['site_assistant_secret'],
	    	'content'=>array(
	    		"tag"=> OrangeSecret::convert_lang($lang['h_accept_comment']),
			    "title"=> OrangeSecret::convert_lang($lang['h_accept_comment']),
			    "link"=> urlencode($siteurl . "/plugin.php?id=orange_secret:detail&lid=" . $secret['id']),
			    "cover_url"=> $siteurl . "/source/plugin/orange_secret/static/images/notice".rand(1,4).".jpg?3",
			    "extra_info"=>array(
		            array(
		            	"key"=> OrangeSecret::convert_lang($lang['h_hide_user']),
		            	"val"=> OrangeSecret::convert_lang($data['content'])
		            )
			    )
	    	)
	    ));
    	C::t('#orange_secret#orange_secret_comment')->insert( $data );
    	C::t('#orange_secret#orange_secret_list')->update( array('last_time'=>$data['add_time']),array('id'=>$data['lid']) );
    	$orange_conf['item_reply'] && notification_add( $secret['uid'], 'system', 'system_notice', array('subject' =>$lang['h_reply_subject'],'message'=>$lang['h_reply_message'], 'from_id' => 0, 'from_idtype' => 'sendnotice'), 1);
    }else if( $act == 'ajax_comment' ){
    	$lid = intval($_POST['lid']);
    	$start = intval($_POST['start']);
    	$condition = array('lid'=>$lid,'orderby'=>1);
    	$comment_list = C::t('#orange_secret#orange_secret_comment')->get_comment_list( $start,$page_limit,$condition );
    	include template('orange_secret:'.$orange_conf['item_style'].'_ajax');
    }
    
?>