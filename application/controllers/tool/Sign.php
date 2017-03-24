<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class sign extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->helper('array');
        $this->common_model->pv_count($_SERVER['PHP_SELF']);
    }

	public function index()
	{
		$data = [
			'title'=>'签到'
		];
		$this->load->view('tool/sign',$data);
	}

    #百洋登录
    function baiy_login()
    {
        $url = 'http://mallapp.baiyjk.com/login/login';

        $filed = array('account','password','udid');
        $post_data = elements($filed,$_POST);
        $post_data['udid']='123456789012345678901234567890123456';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'BaiYangStore/2.0 (iPhone; iOS 9.3; Scale/2.00)');
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
        //print_r($output);
        echo json_encode(json_decode($output)->data->token);
    }
    #百洋签到
    function baiy_sign()
    {
        $url = 'http://mservice.baiyjk.com/wap/integral/sign';

        $filed = array('token');
        $post_data = elements($filed,$_POST);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'BaiYangStore/2.0 (iPhone; iOS 9.3; Scale/2.00)');
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
        //print_r($output);
        echo json_encode(array(json_decode($output)->message,json_decode($output)->data->integral));
    }



    public function julaibao()
    {
        $data = [
            'title'=>'聚来宝注册'
        ];
        $this->load->view('tool/julaibao',$data);
    }

    #聚来宝注册会员
    function julaibao_sign()
    {
        $recommender = @$_POST['recommender'];
        $num = $_POST['num'];
        $num = $num?$num:'10';
        $pass = $_POST['pass'];
        $pass = $pass?$pass:'10';

        if(!$recommender){
            return false;
        }

        $url = 'http://app.julaibao.com/app/RegUser.aspx?refman='.$recommender;

        $user_str = array();

        for($i=1;$i<=$num;$i++){
            $user = '';
            for($i=1;$i<=rand(9,10);$i++){
                $user .= rand(0,9);
            }
            $user_str[]= $user;
            $post_data = array(
                'user'=>$user,
                'loginpwd'=>$pass,
                'repwd'=>$pass,
                'refman'=>$recommender,
                'email'=>$user.'@qq.com',
                'checkCode'=>'',
                'cbRead'=>'on',

            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'BaiYangStore/2.0 (iPhone; iOS 9.3; Scale/2.00)');
            // post数据
            curl_setopt($ch, CURLOPT_POST, 1);
            // post的变量
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            $output = curl_exec($ch);
            curl_close($ch);
        }

        //打印获得的数据
        //print_r($output);
        echo json_encode(implode($user_str,','));
    }
}
