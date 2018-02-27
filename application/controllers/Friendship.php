<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Friendship extends CI_Controller {


    public function __construct(){
        parent::__construct();

        if(!$this->session->userdata('id')){
            echo "<script>window.location.href='/'</script>";
        }

        $this->site_info['head']['this_page']='friendship';
        $this->site_info['is_login'] = $this->session->userdata('id')?1:0;
    }

    #友情链接列表
    public function index()
    {
        $friendship = $this->common_model->get_records('select * from blog_friendship order BY fs_id DESC ');
        $data = [
            'friendships'=>$friendship,
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/friendship',$data);
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
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/friendship_write',$data);
    }

    #修改页面
    function up_friendship($info_id = 0){
        if(!$info_id){
            echo "<script>alert('未选择编辑的内容，请返回选择');history.go(-1);</script>";
        }
        $friendship = $this->common_model->get_record('select * from blog_friendship where fs_id='.$info_id);
        $data = [
            'friendship_info'=>$friendship,
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/friendship_write',$data);
    }

    #发布友情链接
    function add_friendship(){
        $fs_title = $this->input->post('fs_title');
        $pic = $this->input->post('pic');
        $hplink = $this->input->post('hplink');
        $start_time = strtotime($this->input->post('start_time'));
        $end_time = strtotime($this->input->post('end_time'));
        $fs_id = $this->input->post('fs_id');
        $is_on = $this->input->post('is_on');

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
            'is_on'=>$is_on,
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


}
