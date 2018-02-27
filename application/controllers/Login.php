<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {

    public function __construct(){

        parent::__construct();

        $this->user_id = 1;
        $this->site_info['is_login'] = $this->session->userdata('id')?1:0;
    }

	public function index()
	{
        if($this->session->userdata('id')){
            echo "<script>window.location.href='/user'</script>";
        }
		$data = [
			'title'=>'Management- zhuyk',
		];
        $data = array_merge($data,$this->site_info);
		$this->load->view(BLOG.'login',$data);
	}

	public function verification()
	{
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name','账户名','required');
        $this->form_validation->set_rules('pass','密码','required');
        if($this->form_validation->run()==FALSE) exit(json_encode($this->form_validation->error_string()));//账户或密码格式不正确
        $user_name = trim($this->input->post('name'));
        $password = trim($this->input->post('pass'));
        if($this->form_validation->valid_email($user_name)) $fd = 'email';
        else $fd = 'mobile';
        $user = $this->common_model->get_record('SELECT id,login_times,reg_ipaddr,status FROM '.$this->db->dbprefix.'user WHERE '.$fd.' = ? AND password = ?',array($user_name,md5($password)));
        if($user)
        {
            if($user->status==2){
                exit(json_encode(100003));
            }
            if($user->reg_ipaddr){
                $data = array(
                    'login_times' => $user->login_times + 1,
                );
            }else{
                $realip   = $this->getIPaddr();
                $cityname = $this->getCityName($realip);
                $data = array(
                    'login_times' => $user->login_times + 1,
                    'expire_time ' =>0,
                    'reg_ipaddr' => $realip,
                    'reg_cityname' => $cityname
                );
            }

            $this->db->update('user',$data,array('id'=>$user->id));
            $username = $this->common_model->get_record('SELECT nick FROM '.$this->db->dbprefix.'user_space WHERE id='.$user->id);
            $this->com_model->set_session($user->id,$username->nick);

            exit(json_encode(100001));
        }
        else
        {
            exit(json_encode(100002));
        }
	}

	public function qq_login()
	{
        $app_id = "101458705";
        $app_secret = "a7bdc32709b89488f6962aafb1dbae4a";
        $my_url = "http://www.zhuyk.cn/login/qq_login";

        //Step1：获取Authorization Code
        $code = isset($_REQUEST["code"])?$_REQUEST["code"]:'';//存放Authorization Code
        if(empty($code))
        {
            //state参数用于防止CSRF攻击，成功授权后回调时会原样带回
           $_SESSION['state'] = $state = md5(uniqid(rand(), TRUE));

            //拼接URL
            $dialog_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id="
                . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
                . $_SESSION['state'];
            echo("<script> top.location.href='" . $dialog_url . "'</script>");
        }

        //Step2：通过Authorization Code获取Access Token
        if($_REQUEST['state'] == @$_SESSION['state'] || 1) {

            //拼接URL
            $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
                . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
                . "&client_secret=" . $app_secret . "&code=" . $code;
            $response = file_get_contents($token_url);
            //access_token=B1D0C59DB2C4576AB45541CE10C50865&expires_in=7776000&refresh_token=EE50A1A95F0F82B9E25AE811DF837849

            if (strpos($response, "callback") !== false)//如果登录用户临时改变主意取消了，返回true!==false,否则执行step3
            {
                $lpos = strpos($response, "(");
                $rpos = strrpos($response, ")");
                $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
                $msg = json_decode($response);
                if (isset($msg->error)) {
                    echo "<h3>error:</h3>" . $msg->error;
                    echo "<h3>msg :</h3>" . $msg->error_description;
                    exit;
                }
            }else{
                //续期
                //$refresh_token = trim(explode('&',$response)[2],'refresh_token=');
                //$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=refresh_token&". "client_id=" . $app_id . "&client_secret=" . $app_secret . "&refresh_token=" . $refresh_token;
                //$response = file_get_contents($token_url);
            }
        }else{
            echo "未通过验证，只能进行到此";die;
        }

        //Step3：使用Access Token来获取用户的OpenID
        $params = array();
        parse_str($response, $params);//把传回来的数据参数变量化
        $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=".$params['access_token'];
        $str = file_get_contents($graph_url);
        if (strpos($str, "callback") !== false)
        {
            $lpos = strpos($str, "(");
            $rpos = strrpos($str, ")");
            $str = substr($str, $lpos + 1, $rpos - $lpos -1);
        }
        $qquser = json_decode($str);//存放返回的数据 client_id ，openid
        if (isset($qquser->error))
        {
            echo "<h3>error:</h3>" . $qquser->error;
            echo "<h3>msg :</h3>" . $qquser->error_description;
            exit;
        }
        //stdClass Object ( [client_id] => 101458705 [openid] => 8A6AC5D6F5271EFB0F94AC6E4FC4D7FF )
        //$qquser->openid,params['access_token'],params['expires_in'],params['refresh_token']

        //登录操作
        $user = $this->common_model->get_record("select id,login_times,reg_ipaddr,status from user where openid='".$qquser->openid."'");
        if($user)
        {
            if($user->status==2){
                exit(json_encode(100003));
            }
            if($user->reg_ipaddr){
                $data = array(
                    'login_times' => $user->login_times + 1,
                );
            }else{
                $realip   = $this->getIPaddr();
                $cityname = $this->getCityName($realip);
                $data = array(
                    'login_times' => $user->login_times + 1,
                    'expire_time ' =>0,
                    'reg_ipaddr' => $realip,
                    'reg_cityname' => $cityname
                );
            }

            $this->db->update('user',$data,array('id'=>$user->id));
            $username = $this->common_model->get_record('SELECT nick FROM '.$this->db->dbprefix.'user_space WHERE id='.$user->id);
            $this->com_model->set_session($user->id,$username->nick);

            echo "<script>window.close();</script>";

        }else{
            //Step4：使用<span >openid,</span><span >access_token来获取所接受的用户信息。</span>
            $user_data_url = "https://graph.qq.com/user/get_user_info?access_token={$params['access_token']}&oauth_consumer_key={$app_id}&openid={$qquser->openid}&format=json";
            $user_data = file_get_contents($user_data_url);//此为获取到的user信息
            $user_data = json_decode($user_data);

            $realip   = $this->getIPaddr();
            $cityname = $this->getCityName($realip);
            $data = array(
                'openid' => $qquser->openid,
                'access_token ' =>$params['access_token'],
                'expire_time' => $params['expires_in'],
                'login_times' => 1,
                'create_date' => time(),
                'reg_ipaddr' => $realip,
                'reg_cityname' => $cityname
            );
            $this->db->insert('user',$data);

            $id = $this->db->insert_id();
            $data = array(
                'id' => $id,
                'nick' => $user_data->nickname,
                'province ' =>$user_data->province,
                'city' => $user_data->city,
                'sex' => $user_data->gender,
                'birthday' => $user_data->year,
                'pic_path' => $user_data->figureurl_qq_2,
            );
            $this->db->insert('user_space',$data);

            $this->com_model->set_session($id,$user_data->nickname);
            echo "<script>window.close();</script>";
        }

	}


    /*获取真实的IP地址*/
    function getIPaddr()
    {
        static $realip;
        if (isset($_SERVER)){
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")){
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }

    /**
     * 获取 IP  地理位置
     * 淘宝IP接口
     * @Return: array
     */
    function getCityName($ipaddr)
    {
        $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ipaddr;
        $ipaddr=json_decode(file_get_contents($url));
        if((string)$ipaddr->code=='1'){
            return '未知';
        }
        $data = (array)$ipaddr->data;
        if($data['city']==''){return '未知';}
        return $data['city'];
    }

    function leave(){
        $this->com_model->destroy_session();
        redirect('/');
    }

}
