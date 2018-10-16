<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class OrangeSecret {
    const PLUGIN_ID = 'orange_secret';
    
    
    /*
     * 初始化数据，select使用的正确数据格式
     * $data 二维数组
     * $field1 第一个字段名
     * $field2 第二个字段名
     * 返回 二维数组
     */
    public static function initial_data( $data,$field1,$field2,$type ){
        $arr = array();
        foreach( $data as $val ){
            switch( $type ){
                case 1:
                    $arr[$val[$field1]] = $val[$field1];
                    break;
                case 2:
                    $arr[$val[$field1]] = $val[$field2];
                    break;
                case 3:
                    $arr[$val[$field1]][] = $val[$field2];
                    break;
                case 4:
                    $arr[$val[$field1]] = array($val[$field1],$val[$field2]);
                    break;
                case 5:
                    $arr[$val[$field1]][] = $val;
                    break;
                case 6:
                    $arr[$val[$field1]] = $val;
                    break;
                default:
                    break;
            }
        }
        return $arr;
    }
    
    
    /*
     * 数组参数拼接
     * 用于分页时的参数传递
     */
    public static function param_join( $data ){
        $param_data = array();
        foreach( $data as $key=>$val ){
            $param_data[] = $key.'='.$val;
        }
        return implode('&',$param_data);
    }
    
    
    /*
     * 语言包转换中文编码
     * $charset 编码
     * $lang 中文语言
     * 返回转码后的中文
     */
    public static function convert_lang( $lang ){
    	global $_G;
    	$charset = $_G['charset'];
    	header("Content-type: text/html; charset=utf-8");
        $type = mb_detect_encoding($lang, array('ASCII','GB2312','GBK','UTF-8'));
		if( $type == 'UTF-8' ){
			$lang = iconv('UTF-8',$charset,$lang);
		}else{
			$lang = iconv($charset,'UTF-8',$lang);
		}
        return $lang;
    }
    
    /*
     * 语言包转换中文编码
     * $charset 编码
     * $lang 中文语言
     * 返回转码后的中文
     */
    public static function auto_convert( $langs ){
    	foreach( $langs as $key=>&$val ){
    		if( is_array($val) ){
    			$val = self::auto_iconv($val);
    		}else{
    			$val = self::convert_lang($val);
    		}
    	}
    	return $langs;
    }
    
    /*
     * 生成select元素html结构
     * $name select的name属性和id属性值
     * $data select所需要的数据
     * $selected select选中的项
     * $initial 如果存在的时候会创建一个初始化的option
     * 返回生成好的select元素html代码
     */
    public static function create_select($name,$data,$selected,$initial){
        $select = "<select name='$name' id='$name'>";
        if( $initial ){
            $select .= "<option value='".$initial[0]."'>".$initial[1]."</option>";
        }
        foreach( $data as $val ){
            $sed = $selected==$val[0]?'selected':'';
            $select .= "<option value='".$val[0]."' $sed>".$val[1]."</option>";
        }
        $select .= "</select>";
        return $select;
    }
    
    
    /*
     * 数组过滤，过滤非法数据
     * $types 过滤函数（自定义函数先进行声明）
     * array_map 对数组的每一项进行过滤
     * 返回过滤后的数据
     */
    
    public static function check_array( $array,$type=0 ){
        $types = array(
            '0'=>'addslashes',
            '1'=>'intval',
            '2'=>'strtotime',
            '3'=>'dhtmlspecialchars',
            '4'=>'auto_iconv'
            );
        return array_map($types[$type],$array);
    }
    /*
 		数据分组
 	*/
    public static function array_group( $array,$size=4 ){
        $len = ceil( count( $array ) / $size );
		$narr = array();
		for( $i=0; $i<$len;$i++ ){
            $narr[] = array_splice($array,0,$size);
		}
        return array_filter($narr);
    }
    /*
    	返回分页
    */
   	public static function return_page($max,$page,$step){
   		$pages = array(
   			'prev'=>0,
   			'next'=>0,
   			'page'=>array()
   		);
   		if( $page > 1 ){
   			$pages['prev'] = $page-1;
   		}
   		if( $page < $max ){
   			$pages['next'] = $page+1;
   		}
   		if( $max < $step ){
   			for( $i=0;$i<$max;$i++ ){
   				$pages['page'][] = $i+1;
   			}
   		}else{
   			$start = 1;$end = 0;
   			$center = floor($step/2);
   			if( $page <= $center){
   				$end = $step+1;
   			}else if( $page > $max - $center){
   				$start = $max - $step+1;
   				$end = $max+1;
   			}else{
   				$start = $page - $center;
   				$end = $page + $center+1;
   			}
   			for( $i=$start;$i<$end;$i++ ){
   				$pages['page'][] = $i;
   			}
   		}
   		return $pages;
   	}
   	/*
     * 输出json提示信息
     */
    public static function output($success=0, $msg='',$id=0,$status=0) {
        $data['success'] = $success;
        $data['message'] = $msg;
        $data['id'] = $id;
        $data['status'] = $status;
        echo json_encode($data);
        exit;
    }
    
    /*
     * 关键字替换
     */
    public static function replace_keywords( $str,$keywords ){
		return str_replace($keywords,'***',$str);
    }

}
