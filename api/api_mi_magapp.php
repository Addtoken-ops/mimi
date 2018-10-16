<?php
/*
 * @name	消息通知
 * @author	橙纸<690923328@qq.com>
 */
class orange_magapp{
	
	public function __construct($appid, $appsecret) {
		$this->appid = $appid;
        $this->appsecret = $appsecret;
   	}
   	public static function sendAssistantMsg( $data ){
   		if( !$data['host'] || !$data['user_id'] || !$data['secret'] ){
   			return array('success'=>-1,'msg'=>'parameter incomplete');
   		}
   		$url = 	$data['host']."/mag/operative/v1/assistant/sendAssistantMsg?";
   		$url .= "user_id=".$data['user_id']."&type=".$data['type']."&content=".json_encode($data['content']);
   		$url .= "&assistant_secret=".$data['assistant_secret']."&secret=".$data['secret']."&is_push=0";
   		return self::HttpGet($url);
   	}
	/*
	 * httpGet请求
	 */
	public function HttpGet($url) {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    $res = curl_exec($curl);
	    curl_close($curl);
	    $res= json_decode($res, true);
	    return $res;
  	}
  	/*
	 * httpPost请求
	 */
  	public function HttpPost($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $return = curl_exec($ch);
        curl_close($ch);
        $result= json_decode($return, true);
        return $return;
   }
	
}
?>