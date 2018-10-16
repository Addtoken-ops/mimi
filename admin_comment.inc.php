<?php
    if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
            exit('Access Denied');
    }
    require_once dirname(__FILE__) . '/orange_secret.class.php';
    $act = addslashes($_GET['act']);
    $lang = lang('plugin/orange_secret');
    
    /*列表展示*/
    if( !$act ){
    	
    	$perpage = max(20, empty($_GET['perpage']) ? 20 : intval($_GET['perpage']));
		$start_limit = ($page - 1) * $perpage;
		$condition['orderby'] = 1;
        $condition['lid'] = intval($_REQUEST['lid']);
        $condition['content'] = addslashes($_POST['content']);
        $condition['username'] = addslashes($_POST['username']);
        $count = C::t('#orange_secret#orange_secret_comment')->get_comment_count($condition);
        $mpurl = ADMINSCRIPT."?action=plugins&operation=config&do=".$pluginid."&identifier=orange_secret&pmod=admin_comment&".OrangeSecret::param_join($condition);
		$multipage = multi($count, $perpage, $page, $mpurl, 0, 3);
        $item_list = C::t('#orange_secret#orange_secret_comment')->get_comment_list($start_limit,$perpage,$condition);
    	
    	echo <<<SEARCH
            <form method="post" autocomplete="off" id="tb_search" action="$mpurl">
            <table style="padding:10px 0;">
                <tbody>
                    <tr>
                        <th>&nbsp;$lang[a_secret_name]&nbsp;</th><td><input type="text" class="txt" name="username" value="$condition[username]"></td>
                        <th>&nbsp;$lang[a_key_cont]&nbsp;</th><td><input type="text" class="txt" name="content" value="$condition[content]"></td>
                        <th>&nbsp;</th><td><input type="submit" class="btn" value="$lang[a_submit]"></td>
                    </tr>
                </tbody>
            </table>
            </form>
SEARCH;
    	
		showformheader('plugins&operation=config&do='.$pluginid.'&identifier=orange_secret&pmod=admin_comment&act=del', 'enctype');
		showtableheader();
		echo    '<tr class="header"><th></th><th>ID</th><th>'.
					$lang['uid'].'</th><th>'.
					$lang['a_secret_name'].'</th><th>'.
					$lang['a_secret_cont'].'</th><th>'.
					$lang['a_add_time'].'</th><th>'.
					$lang['a_handle'].'</th><th>'.
	                '</th><th></th></tr>';
		foreach($item_list as $list) {
	            echo'<tr class="hover">'.
	                '<th class="td25"><input class="checkbox" type="checkbox" name="delete['.$list['id'].']" value="'.$list['id'].'"></th>'.
	                '<th width="50">'.$list['id'].'</th>'.
	                '<th width="50">'.$list['uid'].'</th>'.
	                '<th width="50">'.$list['username'].'</th>'.
	                '<th>'.$list['content'].'</th>'.
	                '<th width="150">'.date('Y-m-d H:i:s',$list['add_time']).'</th>'.
	                '<th width="50">'
                    	. '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=orange_secret&pmod=admin_comment&act=edit&id='.$list['id'].'">'.$lang['a_edit'].'</a>'
                	.'</th>'.
	                '</tr>';
		}
        showsubmit('submit',$lang['a_del'], $add, '', $multipage);
		showtablefooter();
		showformfooter();
    }
    /*修改商品*/
    else if( $act=='edit' ){
        if(!submitcheck('submit')) {
            $id = intval($_GET['id']);
            $secret = C::t('#orange_secret#orange_secret_comment')->get_comment_first( $id );
            showformheader('plugins&operation=config&do='.$pluginid.'&identifier=orange_secret&pmod=admin_comment&act=edit', 'enctype');
            echo '<input type="hidden" name="id" value="'.$secret['id'].'"/>';
            showtableheader();
            showsetting( $lang['a_secret_cont'], 'content',$secret['content'], 'text');
            showsubmit('submit',$lang['a_submit']);
            showtablefooter();
            showformfooter();
        }else{
            $get_data = OrangeSecret::check_array($_POST,3);
            $data['content'] = $get_data['content'];
            C::t('#orange_secret#orange_secret_comment')->update($data,array('id'=>$get_data['id']));
            cpmsg( $lang['a_success_info'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=orange_secret&pmod=admin_comment', 'succeed');
        }
    }
    /*删除分类*/
    elseif($act == 'del') {
		if(submitcheck('submit')) {
            foreach($_POST['delete'] as $delete) {
                C::t('#orange_secret#orange_secret_comment')->delete(array('id'=>$delete));
            }
            cpmsg( $lang['a_success_info'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=orange_secret&pmod=admin_comment', 'succeed');
        }

    }
?>