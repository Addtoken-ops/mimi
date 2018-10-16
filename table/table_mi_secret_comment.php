<?php
if (!defined('IN_DISCUZ')) {
    exit('Aecsse Denied');
}
class table_orange_secret_comment extends discuz_table{
    public function __construct() {
        $this->_table = 'orange_secret_comment';
        $this->_pk = 'id';
        parent::__construct();
    }
    
    /*
     * 返回用户统计数量
     */
    public function get_comment_count($where){
        $sql = "SELECT count(*) as count FROM %t WHERE 1";
        $condition[] = $this->_table;
        
        if( $where['lid'] ){
            $sql .=" AND lid=%d";
            $condition[] = $where['lid'];
        }
        if( $where['username'] ){
            $sql .=" AND username like %s";
            $condition[] = '%'.$where['username'].'%';
        }
        if( $where['content'] ){
            $sql .=" AND content like %s";
            $condition[] = '%'.$where['content'].'%';
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
    public function get_comment_list( $start,$size,$where ){
        $sql = "SELECT * FROM %t WHERE 1";
        $condition[] = $this->_table;
        
        if( $where['lid'] ){
            $sql .=" AND lid=%d";
            $condition[] = $where['lid'];
        }
        if( $where['username'] ){
            $sql .=" AND username like %s";
            $condition[] = '%'.$where['username'].'%';
        }
        if( $where['content'] ){
            $sql .=" AND content like %s";
            $condition[] = '%'.$where['content'].'%';
        }
        if( $where['orderby'] ){
            $sql .=" ORDER BY add_time desc";
        }else{
        	$sql .=" ORDER BY add_time asc";
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
    public function get_comment_first( $id ){
        return DB::fetch_first("SELECT * FROM %t WHERE id=%d",array($this->_table,$id));
    }
    
    
    /*
     * $data 用户信息数据
     * 返回插入的id
     */
    public function insert( $data ){
    	DB::query("UPDATE %t SET comment_num=comment_num+1 WHERE id=%d",array('orange_secret_list',$data['lid']));
        return DB::insert($this->_table, $data,true);
    }
    
    /*
     * $data 用户更新数据
     * $condition 更新条件
     * 返回更新id 
     */
    public function update( $data,$condition ){
        return DB::update($this->_table, $data,$condition,true);
    }
    
    /*
     * $condition删除用户条件
     */
    public function delete( $condition ){
        return DB::delete($this->_table, $condition);
    }
}
?>