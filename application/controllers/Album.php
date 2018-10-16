<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Album extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->user_id = 1;
        $this->common_model->pv_count($_SERVER['PHP_SELF']);
        $this->site_info['is_login'] = $this->session->userdata('id')?1:0;
    }

    /**
     * 相册列表
     * */
	public function index()
	{
        $sql = '';
        #分类查看
        $group_id = @$_GET['type'];
        $group_id = $group_id?$group_id:0;
        if($group_id){
            $sql = ' and group_id='.$group_id.' ';
            $title = $this->common_model->get_record('select group_name from blog_group where blog_group_id='.$group_id);
            $this->site_info['website_title']  = @$title->group_name.' - 朱耀昆博客';
            $this->site_info['head']['this_page']  = $group_id;
        }
        #标签查看
        $tag_id = @$_GET['tag'];
        $tag_id = $tag_id?$tag_id:0;
        if($tag_id){
            $sql = " and find_in_set('".$tag_id."', tags) ";
            $title = $this->common_model->get_record('select tag_name from blog_tag where tag_id='.$tag_id);
            $this->site_info['website_title']  = @$title->tag_name.' - 朱耀昆博客';
        }
        #搜索查看
        $key = $this->input->get('key');
        $key = $key?$key:'';
        if($key){
            $sql = ' and title like \'%'.$key.'%\' ';
            $this->site_info['website_title']  = @$key.' - 朱耀昆博客';
        }

        $query = $this->common_model->get_record('SELECT COUNT(*) AS total FROM '.$this->db->dbprefix('user_album').' WHERE user_id='.$this->user_id.$sql);
        $total = $query->total;
        $curr_page = $this->uri->segment(4,0);
        $page_size = 10;
        if( ! $curr_page) $curr_page = 1;
        $offset = ($curr_page - 1) * $page_size;
        $album = $this->common_model->get_records('SELECT * FROM '.$this->db->dbprefix.'user_album WHERE user_id = '.$this->user_id.$sql.' LIMIT '.$offset.','.$page_size);

        foreach($album as $key=>$value){
            $album[$key]->images = $this->common_model->get_records('SELECT title,pic_path FROM '.$this->db->dbprefix.'user_image WHERE user_id = '.$this->user_id.' and aid='.$value->id.' LIMIT 0,5');
        }

        $data = array(
            'album'=>$album,
            'paginate' =>  $this->_get_page($total,$curr_page,$page_size,$group_id,'blog_list'),
        );
        $data = array_merge($data,$this->site_info);
        $this->load->view(BLOG.'user_album',$data);
	}

    /**
     * 分页字符串
     *  t 总条数 c 当前页码  p 页码大小 博文分类 访问操作
     * */
    function _get_page($t,$c,$p,$group_id,$type)
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

                if($c > 1) $str .= '<a href="/blog/'.$type.'/'.$group_id.'/'.($c-1).$q_str.'">上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;';
                for($i = $s; $i <= $e ; $i++)
                {
                    $str .= '<a href="/blog/'.$type.'/'.$group_id.'/'.$i.$q_str.'">'.$i.'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
                }
                if($c < $page_total) $str .= '<a href="/blog/'.$type.'/'.$group_id.'/'.($c+1).$q_str.'">下一页</a>';
                $str .= ' 共计 '.$page_total.'页';
            }
        }
        return $str;
    }

    /**
     * 相册图片
     * */
    function images($album_id = 0){

        if(!$album_id)redirect('/');

        $curr_page = $this->uri->segment(4,0);
        $page_size = 30;
        if( ! $curr_page) $curr_page = 1;
        $offset = ($curr_page - 1) * $page_size;
        $images = $this->common_model->get_records('SELECT * FROM '.$this->db->dbprefix.'user_image WHERE aid = '.$album_id.' LIMIT '.$offset.','.$page_size);

        $album = $this->common_model->get_record('SELECT title FROM '.$this->db->dbprefix.'user_album WHERE id = '.$album_id);

        $this->site_info['website_title']  = @$album->title.' - 朱耀昆博客';
        $data = array(
            'album_title'=>$album->title,
            'images'=>$images,
        );
        $data = array_merge($data,$this->site_info);
        $this->load->view(BLOG.'user_album_images',$data);
    }

}
