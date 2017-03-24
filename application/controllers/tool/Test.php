<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class test extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->helper('array');
        $this->common_model->pv_count($_SERVER['PHP_SELF']);
    }

	public function index()
	{
		$data = [
			'title'=>'功能测试'
		];
		$this->load->view('tool/test',$data);
	}

	public function ci_shiwu()
	{
        echo "<pre>
            运行程序为<br />

            $ this ->db->trans_start(true);   //增加参数 true 则为测试模式，所有查询将会回滚<br />
            $ this ->db->query('select * from blog_album where id=30');<br />
            $ this ->db->query(\"update blog_album set title='5555' where id=30\");<br />
            $ this ->db->query(\"update blog_album set title='5555' where user_id=26\");<br />
            $ this ->db->trans_complete();<br />
            </pre>
        ";

        //严格模式
		$this->db->trans_strict(FALSE);

        //禁用事务
        //$this->db->trans_off();

		$this->db->trans_start(true);   //增加参数 true 则为测试模式，所有查询将会回滚
		$this->db->query('select * from blog_album where id=30');
		$this->db->query("update blog_album set title='5555' where id=30");
		$this->db->query("update blog_album set title='5555' where user_id=26");
		$this->db->trans_complete();

        if ($this -> db -> trans_status() === FALSE) {
            print_r(array('errno' => 500, 'errMsg' => '执行失败!'));
        } else {
            print_r(array('errno' => 0, 'errMsg' => '执行成功!'));
        }

        //手动运行事务
        /*
        $this->db->trans_begin();

        $this->db->query('AN SQL QUERY...');
        $this->db->query('ANOTHER QUERY...');
        $this->db->query('AND YET ANOTHER QUERY...');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        */
	}

}
