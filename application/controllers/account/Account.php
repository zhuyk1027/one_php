<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Account extends CI_Controller {

    public function __construct(){

        parent::__construct();

        $this->user_id = $this->session->userdata('id');
        $this->site_info['is_login'] = $this->session->userdata('id')?1:0;
    }

	public function index()
	{
		$data = [
			'title'=>'灰猪账号管理',
			'platform_type'=>array(1=>'社交', 2=>'网购投资', 3=>'工具', 4=>'IT', 5=>'key', 6=>'应用')
		];

        $data = array_merge($data,$this->site_info);
		$this->load->view(ACCOUNT.'account',$data);
	}

    public function get_account_list()
    {
        $platform_type = array(1=>'社交', 2=>'网购投资', 3=>'工具', 4=>'IT', 5=>'key', 6=>'应用',);

        $sn = trim($this->input->post('keyword'),'keyword');

        $arr = array(
            'error'=>1,
            'message'=>'参数错误'
        );

        if(!$sn){   die(json_encode($arr)); }

        $user = $this->common_model->get_records("SELECT * FROM My_account WHERE (platform like '%$sn%' or account like '%$sn%' or remarks like '%$sn%') and user_id=".$this->user_id);

        if(!$user){
            $arr['message'] = "未找到该数据";
        }else{

            $arr['error'] = 0;

            $liststr = '';

            foreach($user as $key=>$value){
                $liststr .= '<div class="list" sn="sn'.$value->id.'" id="sn'.$value->id.'">
        <span class="title">'.$value->platform.'</span>
        <span class="flag">'.$platform_type[$value->platform_type].'</span><br />
        <span class="desc">'.$value->account.'</span>
    </div>
    <hr />';
            }

            $arr['liststr'] = $liststr;
        }
        die(json_encode($arr));
    }

	public function get_account_info()
	{
        $platform_type = array(1=>'社交', 2=>'网购投资', 3=>'工具', 4=>'IT', 5=>'key', 6=>'应用',);

        $sn = trim($this->input->post('sn'),'sn');

        $arr = array(
            'error'=>1,
            'message'=>'参数错误'
        );

        if(!$sn){   die(json_encode($arr)); }

        $user = $this->common_model->get_record("SELECT * FROM My_account WHERE id=$sn and user_id=".$this->user_id);

        if(!$user){
            $arr['message'] = "该账户已移除";
        }else{

            $arr['error'] = 0;

            $user->platform_type = $platform_type[$user->platform_type];

            $arr['account'] = $user;
        }
        die(json_encode($arr));
	}



	/**
	 * 存储账号信息
	 * 参数： string   platform        平台信息
	 * 参数： Int      platform_type   平台类型
	 * 参数： string   platform_link   平台链接
	 * 参数： string   account         账户
	 * 参数： string   password        密码
	 * 参数： string   answer          安全问题
	 * 参数： string   msg             备注
	 * 返回：json数组（状态，提示信息）
	 * **/
	public function add_account()
	{
        $id = trim($this->input->post('sn'),'sn');
        $platform = trim($this->input->post('platform'));
        $platform_type = trim($this->input->post('platform_type'));
        $platform_link = trim($this->input->post('platform_link'));
        $account = trim($this->input->post('account'));
        $password = trim($this->input->post('password'));
        $safe_answer = trim($this->input->post('answer'));
        $remarks = trim($this->input->post('msg'));
        $do = trim($this->input->post('do'));

        $arr = array(
            'error'=>1,
            'message'=>'参数错误'
        );

        if(!$platform || !$account){   die(json_encode($arr)); }

        $data = array(
            'user_id'=>$this->user_id,
            'platform'=>$platform,
            'platform_type'=>$platform_type,
            'platform_link'=>$platform_link,
            'account'=>$account,
            'password'=>$password,
            'safe_answer'=>$safe_answer,
            'remarks'=>$remarks,
        );

        $stu = false;

        if($do=='insert'){
            $stu = $this->db->insert('My_account',$data);
        }else if($do=='update'){
            $stu = $this->db->update('My_account',$data,array('id'=>$id));
        }else{
            $arr['message'] = "我觉得你TM是来找事的是吧";
        }

        if($stu){
            $arr['error'] = 0;
            $arr['message'] = "$platform 账户已存储";
        }else{
            $arr['message'] = "$platform 账户存储失败";
        }
        die(json_encode($arr));
	}

	public function del_account(){

        $id = trim($this->input->post('sn'),'sn');
        $id = $id?$id:0;

        $stu = $this->db->query("delete from My_account where id=$id and user_id=".$this->user_id);

        if($stu){
            die(json_encode(array('error'=>0,'message'=>'已移除')));
        }else{
            die(json_encode(array('error'=>0,'message'=>'移除失败')));
        }

    }



}
