<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
class common_model extends CI_Model
{
	function __construct()
	{
        //$this->load->database();
		parent::__construct();
	}

	function get_page_records($arr)
	{
		foreach($arr as $k => $v)
		{
			$$k = $v;
		}
		unset($arr);
		if( ! isset($sql) || ! isset($page_base)) return false;
		if( ! isset($per_page)) $per_page = 15;
		if( ! isset($offset)) $offset = $this->uri->segment(3, 0);

		if( ! isset($total_rows))
		{
			$query = $this->db->query($sql);
			$total_rows = $query->num_rows();
			$query->free_result();
		}
		if($total_rows == $offset || $total_rows < $offset)
		{
			$offset=0;
		}
		$this->load->library('pagination');
		$this->pagination->initialize(
			array(
					'base_url'		 => $page_base,
					'total_rows'	 => $total_rows,
					'per_page'		 => $per_page,
					'cur_page'       => $offset
			)
		);
		$query = $this->db->query($sql.' LIMIT '.$offset.' ,'.$per_page);
		$data=array(
			'query'    => $query -> result(),
			'paginate' => $this->pagination->create_links()
		);
		$query->free_result();
		return $data;
	}

	function get_pages_records($arr)
	{
		foreach($arr as $k => $v)
		{
			$$k = $v;
		}
		unset($arr);
		if( ! isset($sql) || ! isset($page_base)) return false;
		if( ! isset($per_page)) $per_page = 15;
		if( ! isset($offset)) $offset = $this->uri->segment(3, 0);

		if( ! isset($total_rows))
		{
			$query = $this->db->query($sql);
			$total_rows = $query->num_rows();
			$query->free_result();
		}
		if($total_rows == $offset || $total_rows < $offset)
		{
			$offset=0;
		}
		$this->load->library('paginations');
		$this->paginations->initialize(
			array(
					'base_url'		 => $page_base,
					'total_rows'	 => $total_rows,
					'per_page'		 => $per_page,
					'cur_page'       => $offset
			)
		);
		$query = $this->db->query($sql.' LIMIT '.$offset.' ,'.$per_page);
		$data=array(
			'query'    => $query -> result(),
			'paginate' => $this->paginations->create_links()
		);
		$query->free_result();
		return $data;
	}

	function page($arr)
	{
		foreach($arr as $k => $v)
		{
			$$k = $v;
		}
		unset($arr);
		//if( ! isset($sql) || ! isset($page_base)) return false;
		if( ! isset($per_page)) $per_page = 15;
		if( ! isset($offset)) $offset = $this->uri->segment(3, 0);

		if( ! isset($total_rows))
		{
			$query = $this->db->query($sql);
			$total_rows = $query->num_rows();
			$query->free_result();
		}
		if($total_rows == $offset || $total_rows < $offset)
		{
			$offset=0;
		}
		$this->load->library('paginations');
		$this->paginations->initialize(
			array(
					'base_url'		 => $page_base,
					'total_rows'	 => $total_rows,
					'per_page'		 => $per_page,
					'cur_page'       => $offset
			)
		);
		$query = $this->db->query($sql.' LIMIT '.$offset.' ,'.$per_page);
		$data=array(
			'query'    => $query -> result(),
			'paginate' => $this->paginations->create_links()
		);
		$query->free_result();
		return $data;
	}

	function get_records($sql,$p=array())
	{
		if(empty($p))
		{
			$query = $this->db->query($sql);
		}
		else
		{
			$query = $this->db->query($sql,$p);
		}

		$rs = array();
		if($query->num_rows()>0) $rs = $query->result();
		$query->free_result();
		return $rs;
	}

	function get_record($sql,$p=array())
	{
		if(empty($p))
		{
			$query = $this->db->query($sql);
		}
		else
		{
			$query = $this->db->query($sql,$p);
		}

		$rs = false;
		if($query->num_rows()>0) $rs = $query->row();
		$query->free_result();
		return $rs;
	}

    function replace_tags($str){
        $str = strip_tags($str);
        return $str;
    }
}
?>