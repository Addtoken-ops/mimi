<?php
    if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    require_once dirname(__FILE__) . '/orange_secret.class.php';
    
    $act = addslashes($_GET['act']);
    $uid = $_G['uid'];
    $orange_conf = $_G['cache']['plugin']['orange_secret'];
    $orange_conf['item_color'] = explode("\r\n",$orange_conf['item_color']);
    $is_manage = in_array($uid,array_filter(explode("\r\n",$orange_conf['item_manage'])));
    
    if( !$is_manage ){OrangeSecret::output(0);}
    if( $_GET['formhash'] != FORMHASH ){OrangeSecret::output(-1);}
	
    if( $act == 'secret' ){
    	$type = addslashes($_POST['type']);
    	if( $type == 'delete' ){
    		C::t('#orange_secret#orange_secret_list')->delete( array('id'=>intval($_POST['lid'])) );
    		OrangeSecret::output(1);
    	}else if( $type =='top' ){
    		C::t('#orange_secret#orange_secret_list')->update( array('is_head'=>1),array('id'=>intval($_POST['lid'])) );
    		OrangeSecret::output(1);
    	}else if( $type =='bottom' ){
    		C::t('#orange_secret#orange_secret_list')->update( array('is_head'=>0),array('id'=>intval($_POST['lid'])) );
    		OrangeSecret::output(1);
    	}
    	
    }else if( $act == 'comment' ){
    	$type = addslashes($_POST['type']);
    	if( $type == 'delete' ){
    		C::t('#orange_secret#orange_secret_comment')->delete( array('id'=>intval($_POST['lid'])) );
    		OrangeSecret::output(1);
    	}
    }
    
?>