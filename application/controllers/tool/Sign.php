<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class sign extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('com_model');
        $this->load->model('common_model');
        $this->load->helper('array');
        $this->common_model->pv_count($_SERVER['PHP_SELF']);
    }

	public function index()
	{
        if($this->session->userdata('id')!=1){
            redirect('/');
        }
		$data = [
			'title'=>'批量操作'
		];
		$this->load->view('tool/sign',$data);
	}

    #登录
    function baiy_login($account='',$pass='')
    {
        if($account){
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
        curl_setopt($ch, CURLOPT_USERAGENT, 'BaiYangStore/3.0.0 (iPhone; iOS 9.3; Scale/2.00)');
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
    #抽奖
    function lottery_do($token,$active_id,$url='')
    {
        if(!$url){
            switch($active_id){
                case 7:$url = 'http://mservice.baiyjk.com/wap/active/lottery_520_subway_do';break;
                case 6:$url = 'http://mservice.baiyjk.com/wap/active/lottery_basha_do';break;
                case 5:$url = 'http://mservice.baiyjk.com/wap/active/lottery_520_do';break;
            }
        }

        $post_data['token'] = $token;
        $post_data['active_id'] = $active_id;

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

        return array(json_decode($output)->message,isset(json_decode($output)->data->postion_id)?json_decode($output)->data->postion_id:99);
    }

    #获取批量用户
    function get_user($type=1,$page=1,$pagesize=100){
        $offset = ($page-1)*$pagesize;
        #获取用户
        $sql = "select * from baiyang_account";
        switch ($type){
            case 1:$sql .= " where pay_pass>1";break;
            case 2:$sql .= " where ISNULL(pay_pass)";break;
        }
        $sql .= " limit $offset,$pagesize";
        $data = $this->common_model->get_records($sql);
        if(empty($data)){ echo "暂无用户";die; }
        return $data;
    }
    #批量抽奖
    function lottery($id=0,$type = 1,$page=1,$pagesize=100)
    {
        if(!$id){
            echo "请输入活动ID";die;
        }

        #获取用户
        $data = $this->get_user($type,$page,$pagesize);

        #对应奖品
        $goods = $this->common_model->get_records("select * from baiyang_active_detail where active_id=$id");
        if(empty($goods)){ echo "暂无奖品"; }

        #批量操作
        foreach($data as $key=>$val){
            $token = $this->baiy_login($val->account,$val->password);
            if(!$token){
                echo $val->account.' 密码错误<br />';
            }else{
                $lottery = $this->lottery_do($token,$id,$goods[0]->url);
                foreach($goods as $row){
                    if($row->postion_id==$lottery[1]){
                        echo $val->account.' 获得'.$row->describe.',message:'.$lottery[0].'<br />';
                    }
                }
            }
        }
    }
    #批量签到
    function baiy_all_sign($type=1,$page=1,$pagesize=100)
    {
        #获取用户
        $data = $this->get_user($type,$page,$pagesize);

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

    /*
     * 以下为批量注册用户操作
     * */

    //补充注册功能
    function register($phone = 1,$invite_code = 1,$code = 1){
        //⑨注册
        $param = array(
            'password'=>substr($phone,5),
            'invite_code'=>$invite_code,
            'mobile_code'=>$code,
            'token'=>'c4389008bfefee32e43f41d10998fd0f93c36ccc',
            'mobile'=>$phone,
            'udid'=>'c9b31154-fd5a-35ff-8a27-5f4a3f3bb278',
        );
        $url = 'http://mallapp.baiyjk.com/login/register';

        $is_right = $this->curl_baiy($url,$param);
        print_r($is_right);
    }

    //批量注册功能
    function register_do($is_auto = 1,$invite = 1,$register_num = 1,$j = 0,$error = 0){
        //错误3次之后,停止运行脚本 or 达到数量之后，输出
        if($error>=10 || $register_num==$j){
            if($error>=10){echo "多次获取错误<br />";}

            $date = date('Ymd');
            $myfile = fopen($date."register.txt", "r") or die("Unable to open file!");
            $info = fread($myfile,filesize($date."register.txt"));
            fclose($myfile);

            $info = explode("\r\n",$info);
            foreach($info as $key=>$value){
                echo $value.'<br />';
            }

            exit();
        }

        if($j == 0){
            $this->write_err_info(" \r\n ");
        }

        //①邀请码
        $invite_code = $this->get_invite_code($is_auto,$invite);
        if(!$invite_code){
            $invite_code = $this->get_invite_code($is_auto,1);
        }

        //②获取session存储的神话token
        $token = $this->get_caoma_token();

        //③获取手机号
        $phone = $this->get_caoma_moile($token);
        //$phone = "17097517581";
        echo $phone.'<br />';
        if(strlen($phone)!=11){
            echo '手机号码错误';die;
        }

        //假如获取成功
        if($phone){
            //④判断是否已注册
            $param = array(
                'type'=>1,
                'token'=>'c4389008bfefee32e43f41d10998fd0f93c36ccc',
                'mobile'=>$phone,
            );
            $url = 'http://mallapp.baiyjk.com/login/check_user_exist';
            $is_cunzai = $this->curl_baiy($url,$param);

            if($is_cunzai->code != 200){
                echo $phone."已经注册";
                $this->write_err_info($phone.'已经注册');
                $error++;
                $this->jump_register_url($is_auto,$invite,$register_num,$j,$error);die;
            }

            //⑤检验邀请码
            $param = array(
                'invite_code'=>$invite_code,
                'token'=>'c4389008bfefee32e43f41d10998fd0f93c36ccc',
            );
            $url = 'http://mallapp.baiyjk.com/cps_user/check_invite_code';
            $is_cunzai = $this->curl_baiy($url,$param);

            if($is_cunzai->code != 200){
                echo $invite_code."邀请码错误";      //邀请码发生问题直接停止
                $this->write_err_info($phone.'注册失败'.$invite_code.'邀请码错误');
                $error++;
                $this->jump_register_url($is_auto,$invite,$register_num,$j,$error);die;
            }

            //⑥根据手机号发送验证码
            $param = array(
                'code_type'=>1,
                'token'=>'c4389008bfefee32e43f41d10998fd0f93c36ccc',
                'nonce_str'=>'z5anobk7wnygeddpz17t79yapim8vh80',
                'mobile'=>$phone,
            );
            $sign = $this->get_sign($param);
            $param['sign'] = $sign;
            $url = 'http://mallapp.baiyjk.com/mobile_code/send_mobile_code';
            $is_send = $this->curl_baiy($url,$param);
            if($is_send->code != 200){
                echo $phone."发送验证码失败";      //邀请码发生问题直接停止
                $this->write_err_info($phone.'注册失败,发送验证码失败');
                $error++;
                $this->jump_register_url($is_auto,$invite,$register_num,$j,$error);die;
            }

            sleep(5);

            //⑦获取手机验证码
            $code = $this->get_mobile_code($token,$phone);
            $num = 1;
            while($code<3 &&$num<10){
                echo "第".$num."次获取失败<br />";
                $num = $num+1;
                sleep(5);
                $code = $this->get_mobile_code($token,$phone);
                if(strpos($code,'MSG&32756') !== false){
                    $num = 10;
                }
            };
            $code_str = $code;
            if(strpos($code,'MSG&32756') == false){
                $code = explode('&',$code);
                $code = substr(trim($code[3],'【百洋商城】您的验证码是'),0,4);
            }else{
                echo $phone."获取验证码失败";      //邀请码发生问题直接停止
                $this->write_err_info($phone.'注册失败,获取验证码失败');
                $error++;
                $this->jump_register_url($is_auto,$invite,$register_num,$j,$error);die;
            }

            //⑧检验验证码
            $param = array(
                'mobile_code'=>$code,
                'token'=>'c4389008bfefee32e43f41d10998fd0f93c36ccc',
                'mobile'=>$phone,
            );
            $url = 'http://mallapp.baiyjk.com/login/check_mobile_code';

            $is_right = $this->curl_baiy($url,$param);
            if($is_right->code != 200){
                echo $phone."验证码错误";      //邀请码发生问题直接停止
                $this->write_err_info($phone.'注册失败,验证码错误，获取到的验证码为'.$code_str);
                $error++;
                $this->jump_register_url($is_auto,$invite,$register_num,$j,$error);die;
            }

            $udid = $this->get_udid();

            //⑨注册
            $param = array(
                'password'=>substr($phone,5),
                'invite_code'=>$invite_code,
                'mobile_code'=>$code,
                'token'=>'c4389008bfefee32e43f41d10998fd0f93c36ccc',
                'mobile'=>$phone,
                'udid'=>$udid,
            );
            $url = 'http://mallapp.baiyjk.com/login/register';

            $is_right = $this->curl_baiy($url,$param);
            if($is_right->code != 200){
                echo $phone."注册失败";      //邀请码发生问题直接停止
                $this->write_err_info($phone.'注册失败,获取验证码失败');
                $error++;
                $this->jump_register_url($is_auto,$invite,$register_num,$j,$error);die;
            }

            //注册成功后跳转至当前页，避免超时
            $j = $j+1;
            $date = date('Ymd');
            $myfile = fopen($date."register.txt", "a+") or die("Unable to open file!");
            $txt = date("Y-m-d H:i").' '.$phone." ".$invite_code." ".$j."\r\n";
            fwrite($myfile, $txt);
            fclose($myfile);
            $this->jump_register_url($is_auto,$invite,$register_num,$j,$error);

        } else{
            //获取手机号失败跳转至当前页重新获取
            $error += 1;
            $this->jump_register_url($is_auto,$invite,$register_num,$j,$error);
        }
    }

    /*获取udid*/
    function get_udid(){
        $udid = array(
            '72BADF9B-DDA3-440C-9C7C-D2D31F75CFCD',
            '041E2CA8-7340-41F1-A1A2-1C0AB4F5690A',
            '490D3C4A-73BB-4E24-B635-6FEA769508FC',
            '94E3872F-D158-4785-8453-D156B2C99ADD',
            'F619C7BC-94FB-4B86-9FE8-86FA868B43A9',
            '2D4A6369-B040-4677-9F5E-0D0165EDBBED',
            'C0EC1F92-FDCF-4079-91E6-C3F66C8CBD5A',
            '72BADF9B-DDA3-440C-9C7C-D2D31F75CFCD',
            'C3A127E1-ACCE-45AE-BC9F-2A2933B496B6',
            '5F46EA2E-02C4-4BA9-8799-EEE6472587A1',
            '2839894D-1B77-4F6E-828D-7511CABD5DFE',
            '2044F664-CEEA-48A0-8139-8AA834D5321D',
            'E3C0658E-DC8A-4F44-A5AA-DFB15FDEF9E3',
            'D9E0EE9F-96E1-4B68-9F12-8AFE48CB0F25',
            '2839894D-1B77-4F6E-828D-7511CABD5DFE',
            'D9E0EE9F-96E1-4B68-9F12-8AFE48CB0F25',
            '8D2236A0-6D04-4233-957A-D4EA7B62C05B',
            'ED2F481B-148C-45E8-9BDA-7D01745D5DDA',
            '6D3DF893-9139-4FBE-913C-C62C26BFFEC1',
            '2839894D-1B77-4F6E-828D-7511CABD5DFE',
            '4B30AEBC-8134-400C-A5AB-7A9466BA7083',
            'D9E0EE9F-96E1-4B68-9F12-8AFE48CB0F25',
            'BFFDF023-2247-4770-B92C-E319768FC4A2',
        );

        return $udid[rand(0,count($udid)-1)];
    }

    /** 单次成功后跳转 */
    function jump_register_url($is_auto,$invite,$register_num,$j,$error){
        $url = '/tool/sign/register_do/'.$is_auto.'/'.$invite.'/'.$register_num.'/'.$j.'/'.$error;
        echo "<script>window.location.href='".$url."'</script>";exit();
    }

    /*获取邀请码*/
    function get_invite_code($is_auto,$invite){
        if($is_auto==1){
            #取随机一个
            $info = $this->common_model->get_records(
                "select * from tags where type_of='invite'"
            );
            $info = $info[rand(0,count($info)-1)];
        }else{
            $info = $this->common_model->get_record("select * from tags where type_of='invite' and id=$invite");
        }
        return isset($info->val)?$info->val:'';
    }

    /**
     * 获取神话token
     * @return string
     */
    function get_caoma_token(){
        $token = isset($_SESSION['SHENHUA_TOKEN'])?$_SESSION['SHENHUA_TOKEN']:'';
        if(!$token){
            $param = array(
                'uName'=>'zhuyk',
                'pWord'=>'qqqqqq',
                'Developer'=>'B0LlCpIV0So3jveXXotoHg%3d%3d',
            );
            $url = '/pubApi/uLogin';
            $token = $this->curl_caoma($url,$param);
            //登录token                                 &   账户余额 & 最大登录客户端个数 & 最多获取号码数 & 单个客户端最多获取号码数 & 折扣
            //JoHv0JL1oAsFQqbum69eQokdYpEUhpF11F2072539 &   9.900    &   100              &  30            &   30                     &   1   &   0.000
            $token = explode('&',$token);
            $token = $token[0];
            $_SESSION['SHENHUA_TOKEN'] = $token;
        }

        return $token;
    }

    /**
     * 获取草码手机号
     * @return string
     */
    function get_caoma_moile($token){
        $param = array(
            'token'=>$token,
            'ItemId'=>'32756',
            'Count'=>'1',
            'PhoneType'=>rand(1,3),
            //'onlyKey'=>1,
        );
        $url = '/pubApi/GetPhone';
        $mobile_info = $this->curl_caoma($url,$param);
        $mobile_info = trim($mobile_info,';');
        return $mobile_info;
    }

    /**
     * 释放草码手机号
     * @return string
     */
    function sf_phone($token,$mobile){
        $param = array(
            'mobile'=>$mobile,
            'uid'=>'zhuyk111',
            'token'=>$token,
            'size'=>1,
        );
        $url = 'ReleaseMobile';
        $mobile_info = $this->curl_caoma($url,$param);

        return $mobile_info;
    }

    /**
     * 加黑草码无用手机号
     * @return string
     */
    function add_black_mobile($token,$phone){
        $param = array(
            'token'=>$token,
            'phoneList'=>'32756-'.$phone,
        );
        $url = '/pubApi/AddBlack';
        $info = $this->curl_caoma($url,$param);
        return $info;
    }

    /**
     * 获取手机验证码内容
     * @return string
     */
    function get_mobile_code($token,$phone){
        $param = array(
            'token'=>$token,
            'ItemId'=>'32756',
            'Phone'=>$phone,
        );
        $url = '/pubApi/GMessage';
        $info = $this->curl_caoma($url,$param);

        return $info;
    }

    /**
     * @desc curl 神话
     */
    public function curl_caoma($url_str,$param) {
        if(!$url_str || !$param){
            return false;
        }

        $url = 'http://api.shjmpt.com:9002'.$url_str.'?';
        $paramstr = '';
        foreach($param as $key=>$val){
            $paramstr .= $key.'='.$val.'&';
        }
        $url .= trim($paramstr,'&');

        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        //打印获得的数据
        return $output;
    }

    /**
     * @desc curl 白羊
     */
    public function curl_baiy($url,$param) {
        if(!$url || !$param){
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'BaiYangStore/3.3.0 (iPhone; iOS 9.3; Scale/2.00)');
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $output = curl_exec($ch);
        curl_close($ch);

        $output = json_decode($output);
        return $output;
    }

    /** 获取百洋签名 */
    public function get_sign($param){
        $sign = md5($this->post_to_string($this->order_array($param)));
        return $sign;
    }
    function order_array($array)
    {
        if (!is_array($array) || empty($array) || !isset($array['nonce_str'])) {
            return false;
        }
        $first_str = ord($array['nonce_str']);
        if ($first_str % 2 == 1) {
            krsort($array);
        } else {
            ksort($array);
        }
        return $array;
    }
    function post_to_string($post_data, $sign_key = 'CAqlz8C')
    {
        $string = '';
        foreach ($post_data as $key => $value) {
            if (isset($value) && ($value != '' || $value === 0) && ($key != 'sign')) {
                $string .= $key . '=' . $value . '&';
            }
        }
        $string .= 'key=' . $sign_key;
        return $string;
    }

    function unset_session(){
        unset($_SESSION['SHENHUA_TOKEN']);
    }

    function write_err_info($txt){
        $date = date('Ymd');
        $myfile = fopen($date."register.txt", "a+") or die("Unable to open file!");
        fwrite($myfile, date("Y-m-d H:i").' '.$txt."\r\n");
        fclose($myfile);
    }

    /*
     * 以上为批量注册用户操作
     * */

    #聚来宝注册页面
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
        echo json_encode(implode($user_str,','));
    }
}
