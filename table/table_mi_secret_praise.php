<?php
if (!defined('IN_DISCUZ')) {
    exit('Aecsse Denied');
}
class table_orange_secret_praise extends discuz_table{
    public function __construct() {
        $this->_table = 'orange_secret_praise';
        $this->_pk = 'id';
        parent::__construct();
    }
    
    /*
     * 返回用户统计数量
     */
    public function get_praise_count($where){
        $sql = "SELECT count(*) as count FROM %t WHERE 1";
        $condition[] = $this->_table;
        
        if( $where['is_head'] ){
            $sql .=" AND is_head=%d";
            $condition[] = $where['is_head'];
        }
        if( $where['status'] > -1 ){
            $sql .=" AND status=%d";
            $condition[] = $where['status'];
        }
        if( $where['user_name'] ){
            $sql .=" AND user_name like %s";
            $condition[] = '%'.$where['user_name'].'%';
        }
        
        $count = DB::fetch_first($sql,$condition);
        return $count['count'];
    }
    /*
     * $start 开始位置
     * $size 记录数量
     * $status 商品状态 (0为全部，1开启)
     * 返回指定数量的分类集合
     */
    public function get_praise_list( $start,$size,$where ){
        $sql = "SELECT * FROM %t WHERE 1";
        $condition[] = $this->_table;
        
        if( $where['is_head'] ){
            $sql .=" AND is_head=%d";
            $condition[] = $where['is_head'];
        }
        if( $where['status'] > -1 ){
            $sql .=" AND status=%d";
            $condition[] = $where['status'];
        }
        if( $where['user_name'] ){
            $sql .=" AND user_name like %s";
            $condition[] = '%'.$where['user_name'].'%';
        }
        if( $where['orderby'] ){
            $sql .=" ORDER BY is_head desc,add_time desc,sorting asc ";
        }else{
        	$sql .=" ORDER BY add_time desc";
        }
        
        $sql .= " LIMIT %d,%d";
        $condition[] = $start;
        $condition[] = $size;
        return DB::fetch_all($sql,$condition);
    }
    
    
    /*
     * $uid 获取指定UID的用户
     * 返回一条用户
     */
    public function get_user_praise( $ids,$uid ){
    	if( !$ids || !$uid ){
    		return array();
    	}
    	if( is_array( $ids ) ){
    		return DB::fetch_all("SELECT id,lid FROM %t WHERE lid in (".implode(',',$ids).") AND uid=%d",array($this->_table,$uid));	
    	}else{
    		return DB::fetch_first("SELECT id,lid FROM %t WHERE lid=%d AND uid=%d",array($this->_table,$ids,$uid));
    	}
        
    }
    
    
    /*
     * $data 用户信息数据
     * 返回插入的id
     */
    public function insert( $data ){
    	DB::query("UPDATE %t SET praise_num=praise_num+1 WHERE id=%d",array('orange_secret_list',$data['lid']));
        return DB::insert($this->_table, $data,true);
    }
    
    /*
     * $condition删除用户条件
     */
    public function delete( $condition ){
    	DB::query("UPDATE %t SET praise_num=praise_num-1 WHERE id=%d",array('orange_secret_list',$condition['lid']));
        return DB::delete($this->_table, $condition);
    }
    
}
?>