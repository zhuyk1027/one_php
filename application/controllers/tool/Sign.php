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

    #登录
    function baiy_login($account='',$pass='')
    {
        if($account){
            #$url = 'http://mservice.baiyjk.com/wap/login/login';
            $url = 'http://mallapp.baiyjk.com/login/login';
            $post_data['account'] = $account;
            $post_data['password'] = $pass;
            $post_data['udid']='123456789012345678901234567890123456';
        }else{
            $url = 'http://mallapp.baiyjk.com/login/login';
            $filed = array('account','password','udid');
            $post_data = elements($filed,$_POST);
            $post_data['udid']='123456789012345678901234567890123456';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'BaiYangStore/3.0.0 (iPhone; iOS 9.3; Scale/2.00)');
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
        #print_r($output);die;

        if($account){
            if(isset(json_decode($output)->data->token)){
                return json_decode($output)->data->token;
            }else{
                return '';
            }
        }else{
            echo json_encode(json_decode($output)->data->token);
        }
    }
    #签到
    function baiy_sign($token='')
    {
        $url = 'http://mservice.baiyjk.com/wap/integral/sign';

        if($token){
            $post_data['token'] = $token;
        }else{
            $filed = array('token');
            $post_data = elements($filed,$_POST);
        }

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

        if($token){
            return array(json_decode($output)->message,json_decode($output)->data->integral);
        }else{
            echo json_encode(array(json_decode($output)->message,json_decode($output)->data->integral));
        }
    }

    #批量签到
    function baiy_all_sign()
    {
        #获取用户
        $data = $this->common_model->get_records("select * from baiyang_account");
        if(empty($data)){ echo "暂无用户"; }

        #批量操作
        foreach($data as $key=>$val){
            $token = $this->baiy_login($val->account,$val->password);
            if(!$token){
                echo $val->account.' 密码错误<br />';
            }else{
                $sign_info = $this->baiy_sign($token);
                echo $val->account.' 获得'.$sign_info[1].'积分'.',message:'.$sign_info[0].'<br />';
            }
        }
    }
    #批量520抽奖
    function baiy_520_lottery()
    {
        #获取用户
        $data = $this->common_model->get_records("select * from baiyang_account");
        if(empty($data)){ echo "暂无用户"; }

        #批量操作
        foreach($data as $key=>$val){
            $token = $this->baiy_login($val->account,$val->password);
            if(!$token){
                echo $val->account.' 密码错误<br />';
            }else{
                $lottery = $this->lottery_520($token);
                $arr = array(
                    '1'=>'免单',
                    '6'=>'全场95折优惠卷',
                    '5'=>'全场9折优惠卷',
                    '4'=>'全场88折优惠卷',
                    '3'=>'全场85折优惠卷',
                    '2'=>'全场8折优惠卷',
                    '99'=>'抽奖错误',
                );
                echo $val->account.' 获得'.$arr[$lottery[1]].',message:'.$lottery[0].'<br />';
            }
            die;
        }
    }
    #520抽奖
    function lottery_520($token,$active_id=5)
    {
        $url = 'http://mservice.baiyjk.com/wap/active/lottery_basha_do';

        $post_data['token'] = $token;
        $post_data['active_id'] = $active_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
        //print_r($output);

        return array(json_decode($output)->message,isset(json_decode($output)->data->postion_id)?json_decode($output)->data->postion_id:99);
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
