<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Friendship extends CI_Controller {
    public $user_id = 0;
    public $site_info = array(
        'home'=>'<a href="/homepage">Home</a>',
        'crumbs'=>'',
    );
    public function __construct()
    {
        parent::__construct();
        $this->load->model('com_model');
        if($this->session->userdata('id')=='')redirect('/smg');
        $user_id = $this->user_id = (int)$this->session->userdata('id');
    }

    #友情链接列表
	public function index()
	{
		$friendship = $this->common_model->get_records('select * from blog_friendship order BY fs_id DESC ');
		$data = [
			'friendship'=>$friendship,
		];
        $this->site_info['crumbs'] = '<a href="#">友情链接</a>';
        $data = array_merge($data,$this->site_info);
		$this->load->view('smg/friendship',$data);
	}
    #删除连接
    function del_friendship(){
        $id = $this->input->post('id');
        if(!$id)echo json_encode(-2);

        $info = $this->common_model->get_record('select * from blog_friendship where fs_id='.$id);
        if(!$info){ echo json_decode(3); }

        $this->db->query('delete from '.$this->db->dbprefix.'blog_friendship where fs_id='.$id);
        if($this->db->affected_rows()>0){
            echo json_encode(1);
        }else{
            echo json_encode(2);
        }
    }

    #发布友情链接页面
    function write_friendship(){
        $data = array();
        $this->site_info['crumbs'] = '<a href="#">添加友情链接</a>';
        $data = array_merge($data,$this->site_info);
        $this->load->view('smg/write_friendship',$data);
    }
    #发布友情链接
    function add_friendship(){
        $fs_title = $this->input->post('fs_title');
        $pic = $this->input->post('pic');
        $hplink = $this->input->post('hplink');
        $start_time = strtotime($this->input->post('start_time'));
        $end_time = strtotime($this->input->post('end_time'));
        $fs_id = $this->input->post('fs_id');

        if(!$fs_title ||!$hplink ||!$start_time || !$end_time ){
            echo json_encode(3);exit;
        }

        $data = array(
            'fs_title'=>$fs_title,
            'pic'=>$pic,
            'hplink'=>$hplink,
            'start_time'=>$start_time,
            'end_time'=>$end_time,
            'create_time'=>time(),
        );
        if($fs_id){
            $data['update_time'] = time();
            $res = $this->db->update('blog_friendship',$data,array('fs_id'=>$fs_id));
        }else{
            $res = $this->db->insert('blog_friendship',$data);
        }

        if($res){
            echo json_encode(1);exit;
        }else{
            echo json_encode(2);exit;
        }
    }

    #修改博文
    function up_friendship($info_id = 0){
        if(!$info_id){
            echo "<script>alert('未选择编辑的内容，请返回选择');history.go(-1);</script>";
        }
        $friendship = $this->common_model->get_record('select * from blog_friendship where fs_id='.$info_id);
        $data = [
            'friendship'=>$friendship,
        ];
        $this->site_info['crumbs'] = '<a href="#">修改友情链接</a>';
        $data = array_merge($data,$this->site_info);
        $this->load->view('smg/write_friendship',$data);
    }

}
