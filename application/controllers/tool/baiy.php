<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class baiy extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('com_model');
        $this->load->helper('array');
    }

	public function index()
	{
		$data = [
			'title'=>'白羊'
		];
		$this->load->view('tool/sign',$data);
	}

    /*-------------------------------------------------------百洋 start---------------------------------------------------------------*/

    //登录
    function baiy_login($account='',$pass='')
    {
        $url = 'https://www.baiyangwang.com/mobile/index.php?act=login';

        $post_data = array(
            'username'=>$account,
            'password'=>$pass,
            'client'=>'wap',
            'sec_val'=>'',
            'sec_key'=>'e68967bb30e0728d8b1f568a74847b1c',
            'login_yzm'=>'0',
        );

        //{"code":200,"datas":{"username":"zhuyk","userid":"617409","key":"57973da9dfbd1343c902d0b9c293d3a0"}}
        $output = $this->curl_baiy($url,$post_data);

        if($output->code==200){
            echo $output->datas->key;
        }else{
            echo $account.'登陆信息错误';die;
        }

    }
    //签到
    function baiy_sign($token='')
    {
        $url = 'http://mservice.baiyjk.com/wap/integral/sign';

        $post_data = array(
            'token'=>$token,
        );

        $output = $this->curl_baiy($url,$post_data);
        print_r($output);die;

        if($token){
            return array($output->message,$output->data->integral);
        }else{
            echo json_encode(array($output->message,$output->data->integral));
        }
    }
    //抽奖
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

        $output = $this->curl_baiy($url,$post_data);

        return array($output->message,isset($output->data->postion_id)?$output->data->postion_id:99);
    }
    //查看返利信息
    function show_back($token,$start,$end)
    {

        $url = 'http://mallapp.baiyjk.com/v3_3/cps_user/cps_user_promoter_income';

        $post_data['token'] = $token;
        $post_data['time_name'] = 'other_day';
        $post_data['start_time'] = $start;
        $post_data['end_time'] = $end;

        $output = $this->curl_baiy($url,$post_data);

        if($output->code==200){
            $arr['back_amount'] = $output->data->back_amount;
            $arr['effect_order_num'] = $output->data->effect_order_num;
            $arr['register_num'] = $output->data->register_num;
            $arr['message'] = 'SUCCESS';
        }else{
            $arr['back_amount'] = 0;
            $arr['effect_order_num'] = 0;
            $arr['register_num'] = 0;
            $arr['message'] = 'FAIL';
        }
        return $arr;
    }
    //补充注册功能
    function register($phone = 1,$invite_code = 1,$code = 1){
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

    //获取批量用户
    function get_user($type=1,$page=1,$pagesize=100){
        $offset = ($page-1)*$pagesize;
        //获取用户
        $sql = "select * from baiyang_account";
        switch ($type){
            case 1:$sql .= " where pay_pass>1";break;           //有支付密码的
            case 2:$sql .= " where ISNULL(pay_pass)";break;     //无支付密码的
            case 3:$sql .= " where is_cps=1";break;             //cps用户
        }
        $sql .= " limit $offset,$pagesize";
        $data = $this->common_model->get_records($sql);
        if(empty($data)){ echo "暂无用户";die; }
        return $data;
    }
    //批量抽奖
    function lottery($id=0,$type = 1,$page=1,$pagesize=100)
    {
        if(!$id){
            echo "请输入活动ID";die;
        }

        #获取用户
        $data = $this->get_user($type,$page,$pagesize);

        #对应奖品
        $goods = $this->common_model->get_records("select * from baiyang_active_detail where active_id=$id");
        if(empty($goods)){ echo "暂无奖品";die; }

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
    //批量签到
    function baiy_all_sign($type=1,$page=1,$pagesize=100)
    {
        #获取用户
        $data = $this->get_user($type,$page,$pagesize);

        #批量操作
        foreach($data as $key=>$val){
//            $token = $this->baiy_login($val->account,$val->password);
            $token = '3066c34b27549031a0ef5e557850ddef';
            if(!$token){
                echo $val->account.' 密码错误<br />';
            }else{
                $sign_info = $this->baiy_sign($token);
                echo $val->account.' 获得'.$sign_info[1].'积分'.',message:'.$sign_info[0].'<br />';
            }
        }
    }
    //批量查看
    function show_backmoney($type=1)
    {
        //php获取本月起始时间戳和结束时间戳
        if($type==1){
            $strat=mktime(0,0,0,date('m'),1,date('Y'));
            $end=mktime(23,59,59,date('m'),date('t'),date('Y'));
            $date = 'tpl/register_log/'.date('Ym');
        }else{
            $strat=mktime(0,0,0,date('m')-1,1,date('Y'));
            $end=mktime(0,0,0,date('m'),1,date('Y'))-1;
            $date = 'tpl/register_log/'.date("Ym", strtotime("-1 month"));
        }

        #获取用户
        $data = $this->get_user(3);
        $back_money = 0;

        $myfile = fopen($date.".txt", "a+") or die("Unable to open file!");
        $txt = '';
        #批量操作
        foreach($data as $key=>$val){
            $token = $this->baiy_login($val->account,$val->password);
            if(!$token){
                echo $val->account.' 密码错误<br />';
            }else{
                $arr = $this->show_back($token,$strat,$end);
                if($arr['back_amount']>0){
                    echo $val->account.' 返利'.$arr['back_amount'].
                        ',注册用户:'.$arr['register_num'].'，返利订单'.$arr['effect_order_num'].'<br />';

                    $back_money += $arr['back_amount'];

                    $txt .= $val->account.' 返利'.$arr['back_amount'].
                        ',注册用户:'.$arr['register_num'].'，返利订单'.$arr['effect_order_num']."\r\n";
                }
            }
        }

        echo "共计".$back_money.'元';
        $txt .= "共计".$back_money.'元';
        fwrite($myfile, $txt."\r\n");
        fclose($myfile);
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

    /*获取邀请码*/
    function get_invite_code($is_auto,$invite){
        if($is_auto==1){
            #取随机一个
            $info = $this->common_model->get_records(
                "select id,invite_code from baiyang_account where is_cps=1 and is_on=1"
            );
            $info = $info[rand(0,count($info)-1)];
        }else{
            $info = $this->common_model->get_record("select id,invite_code from baiyang_account where is_cps=1 and is_on=1 and id=$invite");
        }
        return isset($info->invite_code)?array('invite'=>$info->invite_code,'id'=>$info->id):array();
    }

    /**
     * @desc curl 白羊
     */
    public function curl_baiy($url,$param) {

        if(!$url || !$param){   return false;   }

        $xip = $cip = '125.68.54.'.mt_rand(0,254);
        $header = array('CLIENT-IP:'.$cip, 'X-FORWARDED-FOR:'.$xip,);

        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl,CURLOPT_HTTPHEADER, $header);          //伪造IP，避免短信ip锁定
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);die;//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话

        $tmpInfo = json_decode($tmpInfo);
        return $tmpInfo;
    }

    /*------------------------------百洋 end ---------------------易码 start---------------------------------------*/

    //批量注册功能
    function register_do($is_auto = 1,$invite = 1,$register_num = 1,$j = 0,$error = 0){

        //错误多次之后,停止运行脚本 or 达到数量之后，输出
        if($error>=$register_num || $register_num==$j){

            if($error>=$register_num){echo "多次获取错误<br />";}

            //读取txt文档信息
            $info = $this->raed_info('tpl/register_log/'.date('Ymd')."register.txt");
            $info = explode("\r\n",$info);
            foreach($info as $key=>$value){
                echo $value.'<br />';
            }
            exit();
        }

        //开始空格
        if($j == 0 && $error==0){
            $this->write_err_info(" \r\n ");
        }

        //①邀请码
        $invite_code = $this->get_invite_code($is_auto,$invite);
        if(!$invite_code){
            $invite_code = $this->get_invite_code($is_auto,1);
        }
        $invite_code = $invite_code['invite'];

        //②获取session存储的神话token
        $token = $this->get_yima_token();

        //③获取手机号
        $phone = $this->get_yima_moile($token);
        echo $phone.'<br />';
        if(strlen($phone)!=11){
            echo '手机号码错误'.$phone;die;
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
                $error++;
                $this->add_yima_black_mobile($token,$phone);
                $this->jump_url($is_auto,$invite,$register_num,$j,$error);die;
            }

            //⑤根据手机号发送验证码
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
                $error++;
                $this->add_yima_black_mobile($token,$phone);
                $this->jump_url($is_auto,$invite,$register_num,$j,$error);die;
            }

            sleep(5);

            //⑥获取手机验证码
            $code = $this->get_yima_mobile_code($token,$phone);
            $num = 1;
            while(strpos($code,'success|')===false && $num<11){
                echo "第".$num."次获取失败<br />";
                $num = $num+1;
                sleep(5);
                $code = $this->get_yima_mobile_code($token,$phone);
                if(strpos($code,'success|') !== false){
                    $num = 11;
                }
            };
            if(strpos($code,'success|') === false){
                echo $phone."获取验证码失败";      //邀请码发生问题直接停止
                $error++;
                $this->add_yima_black_mobile($token,$phone);
                $this->jump_url($is_auto,$invite,$register_num,$j,$error);die;
            }else{
                $code = explode('|',$code);
                $code = substr(trim($code[1],'【百洋商城】您的验证码是'),0,4);
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
                $error++;
                $this->add_yima_black_mobile($token,$phone);
                $this->jump_url($is_auto,$invite,$register_num,$j,$error);die;
            }

            //注册成功后跳转至当前页，避免超时
            $j = $j+1;
            $txt = $phone." ".$invite_code." ".$j."\r\n";
            $this->write_err_info($txt);

            $this->sf_yima_phone($token,$phone);

            $this->jump_url($is_auto,$invite,$register_num,$j,$error);

        } else{
            //获取手机号失败跳转至当前页重新获取
            $error += 1;
            $this->jump_url($is_auto,$invite,$register_num,$j,$error);
        }
    }

    /** 单次成功后跳转 */
    function jump_url($is_auto,$invite,$register_num,$j,$error){
        $url = '/tool/sign/register_do/'.$is_auto.'/'.$invite.'/'.$register_num.'/'.$j.'/'.$error;
        echo "<script>window.location.href='".$url."'</script>";exit();
    }

    /**
     * 获取易码token
     * @return string
     */
    function get_yima_token(){
        $token = isset($_SESSION['YIMA_TOKEN'])?$_SESSION['YIMA_TOKEN']:'';

        if(!$token){
            $token= '0044227895dd4a9687bad064227f06f58e915910';
            $_SESSION['YIMA_TOKEN'] = $token;
        }

        return $token;
    }

    /**
     * 获取易码手机号
     * @return string
     */
    function get_yima_moile($token){
        $param = array(
            'action'=>'getmobile',
            'token'=>$token,
            'itemid'=>'649',
        );
        $mobile_info = $this->curl_yima($param);
        $mobile_info = explode('|',$mobile_info)[1];
        return $mobile_info;
    }

    /**
     * 释放易码手机号
     * @return string
     */
    function sf_yima_phone($token,$mobile){
        $param = array(
            'action'=>'release',
            'token'=>$token,
            'itemid'=>649,
            'mobile'=>$mobile,
        );
        $mobile_info = $this->curl_yima($param);

        return $mobile_info;
    }

    /**
     * 加黑易码无用手机号
     * @return string
     */
    function add_yima_black_mobile($token,$phone){
        $param = array(
            'action'=>'addignore',
            'token'=>$token,
            'itemid'=>649,
            'mobile'=>$phone,
        );
        $info = $this->curl_yima($param);
        $this->sf_yima_phone($token,$phone);
        return $info;
    }

    /**
     * 获取手机验证码内容
     * @return string
     */
    function get_yima_mobile_code($token,$phone){
        $param = array(
            'action'=>'getsms',
            'token'=>$token,
            'itemid'=>'649',
            'mobile'=>$phone,
        );
        $info = $this->curl_yima($param);

        return $info;
    }

    /**
     * @desc curl 易码
     */
    public function curl_yima($param) {
        if(!$param){
            return false;
        }

        $url = 'http://api.fxhyd.cn/UserInterface.aspx?';
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

    /** 清除session，更新token */
    function unset_yima_session(){
        unset($_SESSION['YIMA_TOKEN']);
    }

    /*------------------------------易码 end -----------------------------------------------------------*/

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

    /** 读取/写入text文件  */
    function write_err_info($txt){
        $date = 'tpl/register_log/'.date('Ymd');
        $myfile = fopen($date."register.txt", "a+") or die("Unable to open file!");
        fwrite($myfile, date("Y-m-d H:i").' '.$txt);
        fclose($myfile);
    }
    function raed_info($txt_name){
        $myfile = fopen($txt_name, "r") or die("Unable to open file!");
        $info = fread($myfile,filesize($txt_name));
        fclose($myfile);
        return $info;
    }

}
