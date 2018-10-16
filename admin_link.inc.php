<?php
    if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
            exit('Access Denied');
    }
    showformheader('');
    showtableheader();
    showsetting(lang('plugin/orange_secret', 'a_link_url'), 'link',$_G['siteurl'].'plugin.php?id=orange_secret', 'text',0,0,lang('plugin/orange_secret', 'a_link_url_info'));
    showtablefooter();
    showformfooter();
?>