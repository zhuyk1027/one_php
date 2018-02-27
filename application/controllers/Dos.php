<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dos extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->site_info['is_login'] = $this->session->userdata('id')?1:0;
    }

    #首页显示
	public function index()
	{

	}

    #链接跳转
    function jump(){
        $id  = $this->input->post('id');
        if(!$id)return false;

        $friendship = $this->common_model->get_record('select * from blog_friendship where fs_id='.$id);
        if(!$friendship)return false;

        //更改
        $this->db->query("update count_friendship set click=click+1 where fs_id=".$id.' and create_time='.date('Ymd'));
        if($this->db->affected_rows()<1){
            //插入
            $data = array('fs_id'=>$id,'fs_title'=>$friendship->fs_title,'create_time'=>date('Ymd'),'click'=>1);
            $this->db->insert('count_friendship',$data);
        }
        echo json_encode($friendship->hplink);

    }

    #链接跳转
    function jump_ad(){
        $id  = $this->input->post('id');
        if(!$id)return false;

        $ad = $this->common_model->get_record('select * from blog_ad where ad_id='.$id);
        if(!$ad)return false;

        //更改
        $this->db->query("update count_ad set click=click+1 where ad_id=".$id.' and create_time='.date('Ymd'));
        if($this->db->affected_rows()<1){
            //插入
            $data = array('ad_id'=>$id,'ad_title'=>$ad->ad_title,'create_time'=>date('Ymd'),'click'=>1);
            $this->db->insert('count_ad',$data);
        }
        echo json_encode($ad->hplink);

    }
}
