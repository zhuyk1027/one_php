<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Controller {

    public $site_info = array(
        'website_title'=>WEB_NAME,
        'head'=>array(
            //'head_title'=>'朱耀昆博客',
            'design'=>DESIGN,
            'my_photo'=>MY_PHOTO,
            'pay_photo'=>PAY_PHOTO,
            'email'=>EMAIL,
            'this_page'=>'blog',
        ),
        'tags'=>'',
        'groups'=>'',
        'ranking'=>'',
        'friendship'=>'',
    );

    public function __construct(){
        parent::__construct();
        $this->load->model('com_model');

        if(!$this->session->userdata('id')){
            echo "<script>window.location.href='/'</script>";
        }

        $this->user_id = $this->session->userdata('id');
        $this->site_info['friendship'] = $this->common_model->get_records('select fs_title,hplink from blog_friendship where is_on=1');
        $this->site_info['groups'] = $this->common_model->get_records('select blog_group_id,group_name,(select count(*) from blog where group_id=blog_group_id ) as num from blog_group where user_id='.$this->user_id);
        $this->site_info['tags'] = $this->common_model->get_records('select tag_id,tag_name,(select count(*) from blog where tags=tag_id ) as num from blog_tag where user_id='.$this->user_id);
        $this->site_info['ranking'] = $this->common_model->get_records('select blog_id,title from blog order by click desc limit 10');
    }

    function index(){

        $data = array(

        );

        $data = array_merge($data,$this->site_info);
        $this->load->view(BLOG.'user_home',$data);
    }

    #用户博文列表
    function user_blog(){
        $query = $this->common_model->get_record('SELECT COUNT(*) AS total FROM '.$this->db->dbprefix('blog').' WHERE user_id='.$this->user_id);
        $total = $query->total;
        $curr_page = $this->uri->segment(3,0);
        $page_size = 20;
        if( ! $curr_page) $curr_page = 1;
        $offset = ($curr_page - 1) * $page_size;
        $blog = $this->common_model->get_records('SELECT blog_id,title,group_id,update_time,create_date FROM '.$this->db->dbprefix.'blog WHERE user_id = '.$this->user_id.' LIMIT '.$offset.','.$page_size);
        $blog_group = $this->site_info['groups'];
        foreach($blog as $key=>$row){
            $row->group_name = '无分类';
            foreach($blog_group as $line){
                if($row->group_id==$line->blog_group_id){
                    $row->group_name = $line->group_name;
                }
            }
        }

        $data = array(
            'blog'=>$blog,
            'paginate' =>  $this->_get_page($total,$curr_page,$page_size,'user_blog'),
        );

        $data = array_merge($data,$this->site_info);
        $this->load->view(BLOG.'user_blog',$data);
    }

    #分页字符串    t 总条数 c 当前页码  p 页码大小 博文分类 访问操作
    function _get_page($t,$c,$p,$type)
    {
        // $type index search tag
        $str = '';
        if( ! $c) $c = 1;
        if($t > 0 && $p > 0)
        {
            $page_total = ceil($t / $p) ;
            if($c > $page_total) $c = $page_total;
            if($page_total > 1)
            {
                if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) $q_str = '?'.$_SERVER['QUERY_STRING'];
                else $q_str = '';
                if(($s = $c - 4) <= 0) $s = 1;
                if(($e = $c + 4) > $page_total) $e = $page_total;

                if($c > 1) $str .= '<a href="/user/'.$type.'/'.($c-1).$q_str.'">上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;';
                for($i = $s; $i <= $e ; $i++)
                {
                    $str .= '<a href="/user/'.$type.'/'.$i.$q_str.'">'.$i.'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
                }
                if($c < $page_total) $str .= '<a href="/user/'.$type.'/'.($c+1).$q_str.'">下一页</a>';
                $str .= ' 共计 '.$page_total.'页';
            }
        }
        return $str;
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
        $data = [
            'blog_group'=>$group,
            'tags'=>$tags,
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/blog_write',$data);
    }
    #发布博文
    function blog_add(){
        //title group_id tags[] briefly container/content
        $title = $this->input->post('title');
        $is_top = $this->input->post('is_top');
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
            'is_top'=>$is_top,
            'cont'=>$content,
            'briefly'=>$briefly,
            'create_date'=>time(),
        );
        $res = $this->db->insert('blog',$data);
        if($res){
            echo "<script>window.location.href='/user/user_blog'</script>";
        }else{
            echo "<script>alert('发布失败!');window.location.href='/user/user_blog';</script>";
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
        $this->load->view('blog/blog_update',$data);
    }
    #发布修改博文
    function blog_update(){
        $blog_id = $this->input->post('blog_id');
        $title = $this->input->post('title');
        $is_top = $this->input->post('is_top');
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
            'is_top'=>$is_top,
            'cont'=>$content,
            'briefly'=>$briefly,
            'update_time'=>time(),
        );

        $res = $this->db->update('blog',$data,array('blog_id'=>$blog_id,'user_id'=>$this->user_id));
        if($res){
            echo "<script>window.location.href='/user/user_blog';</script>";
        }else{
            echo "<script>alert('发布失败!');window.location.href='/user/user_blog';</script>";
        }
    }


    #我的博文分类列表
    public function blog_type()
    {
        $blog_group = $this->common_model->get_records('select blog_group_id,group_name from blog_group where user_id='.$this->user_id);
        $data = [
            'blog_group'=>$blog_group,
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/user_blog_type',$data);
    }

    #添加博文分组
    function add_group(){
        $title = $this->input->post('group_name');
        if(!$title)echo json_encode(-2);
        $this->db->query('insert into '.$this->db->dbprefix.'blog_group(group_name,user_id) values(\''.$title.'\','.$this->user_id.')');
        if($this->db->affected_rows()>0){
            echo json_encode($this->db->insert_id());
        }else{
            echo json_encode(2);
        }
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
            echo json_encode(-2);die;
        }

        $data = array(
            'group_name'=>$group_name,
        );

        $res = $this->db->update('blog_group',$data,array('blog_group_id'=>$group_id,'user_id'=>$this->user_id));
        if($res){
            echo json_encode($group_id);
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
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/user_blog_tag',$data);
    }

    #删除博文标签
    function del_blog_tag(){
        $id = $this->input->post('tag_id');
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
        $title = $this->input->post('tag_name');
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
            echo json_encode(-2);die;
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
