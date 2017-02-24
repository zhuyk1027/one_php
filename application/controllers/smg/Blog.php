<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Blog extends CI_Controller {
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

    #我的博文列表
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

    #删除博文
    function del_blog(){
        $id = $this->input->post('id');
        if(!$id)echo json_encode(-2);

        $info = $this->common_model->get_record('select * from blog where blog_id='.$id.' and user_id='.$this->user_id);
        if(!$info){ echo json_decode(3); }

        $this->db->query('delete from '.$this->db->dbprefix.'blog where blog_id='.$id);
        if($this->db->affected_rows()>0){
            echo json_encode(1);
        }else{
            echo json_encode(2);
        }
    }

    #发布博文页面
    function write_blog(){
        $group = $this->common_model->get_records('select * from blog_group where user_id='.$this->user_id);
        $tags = $this->common_model->get_records('select * from blog_tag where user_id='.$this->user_id);
        $this->site_info['crumbs'] = '<a href="#">添加博文</a>';
        $data = [
            'blog_group'=>$group,
            'tags'=>$tags,
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('smg/blog_write',$data);
    }
    #发布博文
    function blog_add(){
        //title group_id tags[] briefly container/content
        $title = $this->input->post('title');
        $group_id = $this->input->post('group_id');
        $tags = implode($this->input->post('tags[]'),',');
        $briefly = $this->input->post('briefly');
        $content = $this->input->post('content');

        if(!$group_id || !$title || !$content){
            echo "<script>alert('参数不足!');history.go(-1);</script>";
        }

        $data = array(
            'user_id'=>$this->user_id,
            'title'=>$title,
            'group_id'=>$group_id,
            'tags'=>$tags,
            'cont'=>$content,
            'briefly'=>$briefly,
            'create_date'=>time(),
        );
        $res = $this->db->insert('blog',$data);
        if($res){
            echo "<script>window.location.href='/smg/blog/index'</script>";
        }else{
            echo "<script>alert('发布失败!');window.location.href='/smg/blog/index';</script>";
        }
    }

    #修改博文
    function up_blog($blog_id = 0){
        if(!$blog_id){
            echo "<script>alert('未选择编辑的博文，请返回选择');history.go(-1);</script>";
        }
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
    #发布修改博文
    function blog_update(){
        $blog_id = $this->input->post('blog_id');
        $title = $this->input->post('title');
        $group_id = $this->input->post('group_id');
        $tags = implode($this->input->post('tags[]'),',');
        $briefly = $this->input->post('briefly');
        $content = $this->input->post('content');

        if(!$group_id || !$title || !$content){
            echo "<script>alert('参数不足!');history.go(-1);</script>";
        }

        $data = array(
            'user_id'=>$this->user_id,
            'title'=>$title,
            'group_id'=>$group_id,
            'tags'=>$tags,
            'cont'=>$content,
            'briefly'=>$briefly,
            'create_date'=>time(),
        );

        $res = $this->db->update('blog',$data,array('blog_id'=>$blog_id,'user_id'=>$this->user_id));
        if($res){
            echo "<script>window.location.href='/smg/blog/index';</script>";
        }else{
            echo "<script>alert('发布失败!');window.location.href='/smg/blog/index';</script>";
        }
    }



    #添加博文分组
    function add_group(){
        $title = $this->input->post('title');
        if(!$title)echo json_encode(-2);
        $this->db->query('insert into '.$this->db->dbprefix.'blog_group(group_name,user_id) values(\''.$title.'\','.$this->user_id.')');
        if($this->db->affected_rows()>0){
            echo json_encode($this->db->insert_id());
        }else{
            echo json_encode(2);
        }
    }

    #我的博文分类列表
    public function blog_type()
    {
        $blog_group = $this->common_model->get_records('select blog_group_id,group_name from blog_group where user_id='.$this->user_id);
        $data = [
            'blog_group'=>$blog_group,
        ];
        $this->site_info['crumbs'] = '<a href="#">分类列表</a>';
        $data = array_merge($data,$this->site_info);
        $this->load->view('smg/blog_type',$data);
    }

    #删除博文分类
    function del_blog_type(){
        $id = $this->input->post('id');
        if(!$id)echo json_encode(-2);

        $info = $this->common_model->get_record('select * from blog_group where blog_group_id='.$id.' and user_id='.$this->user_id);
        if(!$info){ echo json_decode(3); }

        $this->db->query('delete from '.$this->db->dbprefix.'blog_group where blog_group_id='.$id);
        if($this->db->affected_rows()>0){
            echo json_encode(1);
        }else{
            echo json_encode(2);
        }
    }

    #修改分类名称
    function up_blog_type(){
        $group_id = $this->input->post('group_id');
        $group_name = $this->input->post('group_name');

        if(!$group_id || !$group_name){
            echo json_encode(3);die;
        }

        $data = array(
            'group_name'=>$group_name,
        );

        $res = $this->db->update('blog_group',$data,array('blog_group_id'=>$group_id,'user_id'=>$this->user_id));
        if($res){
            echo json_encode(1);
        }else{
            echo json_encode(2);
        }
    }




    #我的博文标签列表
    public function blog_tag()
    {
        $blog_tag = $this->common_model->get_records('select * from blog_tag where user_id='.$this->user_id);
        $data = [
            'blog_tag'=>$blog_tag,
        ];
        $this->site_info['crumbs'] = '<a href="#">标签列表</a>';
        $data = array_merge($data,$this->site_info);
        $this->load->view('smg/blog_tag',$data);
    }

    #删除博文标签
    function del_blog_tag(){
        $id = $this->input->post('id');
        if(!$id)echo json_encode(-2);

        $info = $this->common_model->get_record('select * from blog_tag where tag_id='.$id.' and user_id='.$this->user_id);
        if(!$info){ echo json_decode(3); }

        $this->db->query('delete from '.$this->db->dbprefix.'blog_tag where tag_id='.$id);
        if($this->db->affected_rows()>0){
            echo json_encode(1);
        }else{
            echo json_encode(2);
        }
    }

    #添加博文标签
    function add_tag(){
        $title = $this->input->post('title');
        if(!$title)echo json_encode(-2);
        $this->db->query('insert into '.$this->db->dbprefix.'blog_tag(tag_name,user_id) values(\''.$title.'\','.$this->user_id.')');
        if($this->db->affected_rows()>0){
            echo json_encode($this->db->insert_id());
        }else{
            echo json_encode(2);
        }
    }

    #修改标签名称
    function up_blog_tag(){
        $tag_id = $this->input->post('tag_id');
        $tag_name = $this->input->post('tag_name');

        if(!$tag_id || !$tag_name){
            echo json_encode(3);die;
        }

        $data = array(
            'tag_name'=>$tag_name,
        );

        $res = $this->db->update('blog_tag',$data,array('tag_id'=>$tag_id,'user_id'=>$this->user_id));
        if($res){
            echo json_encode(1);
        }else{
            echo json_encode(2);
        }
    }

}
