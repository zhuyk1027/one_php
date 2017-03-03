<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
    public $site_info = array(
        'website_title'=>'朱耀昆博客',
        'head'=>array(
            'head_title'=>'朱耀昆博客',
            'design'=>'享受每一天的生活，做最精彩的自己。',
            'my_photo'=>MY_PHOTO,
            'pay_photo'=>PAY_PHOTO,
            'email'=>EMAIL,
            'this_page'=>'0',
        ),
        'tags'=>'',
        'groups'=>'',
        'ranking'=>'',
        'friendship'=>'',
    );

    public function __construct(){

        parent::__construct();
        $this->load->model('com_model');
        $this->user_id = 1;
        $this->site_info['friendship'] = $this->common_model->get_records('select fs_title,hplink from blog_friendship where is_on=1');
        $this->site_info['groups'] = $this->common_model->get_records('select blog_group_id,group_name,(select count(*) from blog where group_id=blog_group_id ) as num from blog_group where user_id='.$this->user_id);
        $this->site_info['tags'] = $this->common_model->get_records('select tag_id,tag_name,(select count(*) from blog where tags=tag_id ) as num from blog_tag where user_id='.$this->user_id);
        $this->site_info['ranking'] = $this->common_model->get_records('select blog_id,title from blog order by click desc limit 10');
    }

	public function index()
	{
        if(!$this->session->userdata('id')){
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
            $this->com_model->set_session($user->id,$user_name);

            exit(json_encode(100001));
        }
        else
        {
            exit(json_encode(100002));
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
        redirect('smg');
    }

}
