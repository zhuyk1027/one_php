<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class About extends CI_Controller {
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
        $this->user_id = (int)$this->session->userdata('id');
    }

    #关于本站
	public function index()
	{
		$blog = $this->common_model->get_records('select title,blog_id,group_id,create_date from blog where user_id='.$this->user_id);
		$blog_group = $this->common_model->get_records('select blog_group_id,group_name from blog_group where user_id='.$this->user_id);
		$data = [
			'blog'=>$blog,
			'blog_group'=>$blog_group,
		];
        $this->site_info['crumbs'] = '<a href="#">博文列表</a>';
        $data = array_merge($data,$this->site_info);
		$this->load->view('smg/blog_list',$data);
	}

    #修改博文
    function station(){
        print_r($this->user_id);die;
        $group = $this->common_model->get_records('select * from blog_group where user_id='.$this->user_id);
        $info = $this->common_model->get_record('select * from blog where blog_id='.$blog_id.' AND user_id='.$this->user_id);
        $tags = $this->common_model->get_records('select * from blog_tag where user_id='.$this->user_id);
        $data = [
            'blog_group'=>$group,
            'blog'=>$info,
            'tags'=>$tags,
        ];
        $this->site_info['crumbs'] = '<a href="#">修改博文</a>';
        $data = array_merge($data,$this->site_info);
        $this->load->view('smg/blog_update',$data);
    }

}
