<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Gallery extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->helper('common');

        if(!$this->session->userdata('id')){
            echo "<script>window.location.href='/'</script>";
        }

        $this->user_id = $this->session->userdata('id');
        $this->site_info['head']['this_page']='gallery';
        $this->site_info['is_login'] = $this->session->userdata('id')?1:0;
    }

    #相册列表
    public function index()
    {
        $gallery = $this->common_model->get_records('select * from user_album where user_id='.$this->user_id);
        foreach($gallery as $row){
            $num = $this->common_model->get_record('select count(id) as count,pic_path from user_image where aid='.$row->id);
            $row->img_num = $num->count;
            $row->pic_path = $num->pic_path;
            if(!$num->pic_path){
                $row->pic_path = '/tpl/blog/img/thumbnail.png';
            }
        }
        $data = [
            'gallery'=>$gallery,
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/user_gallery_list',$data);
    }

    /* 删除相册 及 相册内图片 */
    function del_album(){

        $id = $this->input->post('id');
        if(!$id)echo json_encode(-2);

        $image = $this->common_model->get_records('select * from user_image where aid='.$id.' and user_id='.$this->user_id);//print_r($image);die;
        foreach($image as $row){
            $this->alido(2,$row->pic_path);
        }
        $this->db->query('delete from '.$this->db->dbprefix.'user_image where aid='.$id);
        $this->db->query('delete from '.$this->db->dbprefix.'user_album where id='.$id);

        if($this->db->affected_rows()>0){
            echo json_encode(1);
        }else{
            echo json_encode(2);
        }
    }

    /* 添加相册 */
    function add_album(){
        $title = $this->input->post('title');
        if(!$title)echo json_encode(-2);

        $this->db->query('insert into '.$this->db->dbprefix.'user_album(title,user_id) values(\''.$title.'\','.$this->user_id.')');

        if($this->db->affected_rows()>0){
            echo json_encode($this->db->insert_id());
        }else{
            echo json_encode(2);
        }
    }

    /* 更改相册信息 */
    function up_album(){

        $title = $this->input->post('title');
        $id = $this->input->post('gallery_id');

        if(!$title)echo json_encode(-2);

        $this->db->query('update '.$this->db->dbprefix.'user_album set title=\''.$title.'\' where id='.$id.' and user_id='.$this->user_id);

        if($this->db->affected_rows()>0){
            echo json_encode(1);
        }else{
            echo json_encode(2);
        }
    }

    #图片操作
    public function images($id = 0)
    {
        $album = $this->common_model->get_record('select title from user_album where id='.$id.' and user_id='.$this->user_id);
        if(!$id){
            $gallery = $this->common_model->get_record('select count(id) as total from user_image where user_id='.$this->user_id);
            $sql = 'select * from user_image where user_id='.$this->user_id;
        }else{
            $gallery = $this->common_model->get_record('select count(id) as total from user_image where aid='.$id.' and user_id='.$this->user_id);
            $sql = 'select * from user_image where user_id='.$this->user_id.' AND aid='.$id;
        }
        $total = $gallery->total;
        $sql .= ' ORDER BY id DESC';

        $page=array(
            'page_base' => '/gallery/images/'.$id,
            'offset'   => $this->uri->segment(4,0),
            'per_page' => 15,
            'sql'  => $sql,
            'total_rows' => $total
        );
        $gallery = $this->common_model->get_pages_records($page);

        foreach($gallery['query'] as $row){
            #$row->pic_path = get_real_path($row->pic_path,'@80h');
            $row->pic_path = get_real_path($row->pic_path,'@!150');
        }
        $data = [
            'gallery'=>$gallery['query'],
            'paginate'=>$gallery['paginate'],
            'album'=>$album,
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/user_images',$data);
    }

    /* 删除图片 */
    function del_img(){

        $id = $this->input->post('id');
        if(!$id)echo json_encode(-2);

        $image = $this->common_model->get_record('select * from user_image where id='.$id.' and user_id='.$this->user_id);
        if(!$image)echo json_encode(3);

        $this->alido(2,$image->pic_path);
        $this->db->query('delete from '.$this->db->dbprefix.'user_image where id='.$id);

        if($this->db->affected_rows()>0){
            echo json_encode(1);
        }else{
            echo json_encode(2);
        }
    }

    #上传图片页面
    public function upload()
    {
        $gallery = $this->common_model->get_records('select * from user_album where user_id='.$this->user_id);
        $data = [
            'gallery'=>$gallery,
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/user_image_upload',$data);
    }

    /* 逐个上传图片 */
    public function upload_form()
    {

        $aid = $this->input->post('aid');
        $imgs = $this->input->post('imgs');
        $alt = $this->input->post('alt');

        /* 验证相册是否存在 */
        $album = $this->common_model->get_record('SELECT * FROM '.$this->db->dbprefix.'user_album WHERE id = '.$aid);
        if( ! $album) return ;

        /* 检测是否是64数据流 */
        if ( ! preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgs, $result)) return ;

        /* 定义图片路径，并写入文件 */
        $pic_url = UP_TEMP_PATH.md5(uniqid(mt_rand())).'.'.$result[2];
        if( ! file_put_contents($pic_url, base64_decode(str_replace($result[1], '', $imgs)))) return ;

        $img_size = getimagesize($pic_url);

        $id = 0;
        //$alt = date('Ymd').$alt;

        if($pic_path = alido(1,$pic_url,$alt,'/images/'.date('Ymd'),rand(0000,99999)))
        {
            $pic_path = $pic_path['url'];
            $save_data['aid'] = $aid;
            $save_data['user_id'] = $this->user_id;
            $save_data['title'] = $alt?$alt:'灰猪领地';
            $save_data['pic_path'] = $pic_path;
            $save_data['width'] = $img_size[0];
            $save_data['height'] = $img_size[1];
            $save_data['create_time'] = time();
            $this->db->insert('user_image',$save_data);
            $id =  $this->db->insert_id();
        }
        unlink($pic_url);
        ob_clean();
        return $id;
    }

    /* 批量上传图片 */
    public function upload_form_bak()
    {
        if(!@$_FILES){
            redirect('/smg/gallery/upload_success/0/0');
        }

        $count = count($_FILES['pic_path']['name']);
        $re_data = array('all'=>$count,'success_num'=>0,'pic_path'=>array(),'id'=>array());
        $tmpname = $_FILES['pic_path']['tmp_name'];
        $name = $_FILES['pic_path']['name'];

        foreach($tmpname as $k=>$row){
            $pic_path = $row;
            $res = $this->alido(1,$pic_path,$name[$k], '/images/'.date('Ymd').'/');
            if($res['isSuccess']==1){

                //加入到用户图片中
                $save_data['aid'] = 0;
                $save_data['user_id'] = $this->user_id;
                $save_data['title'] = $name[$k];
                $save_data['pic_path'] = $res['url'];
                $save_data['width'] = 0;
                $save_data['height'] = 0;
                $save_data['create_time'] = time();
                $this->db->insert('user_image',$save_data);
                $id = $this->db->insert_id();

                $re_data['success_num'] += 1;
                $re_data['pic_path'][] = $res['url'];
                $re_data['id'][] = $id;
            }
            sleep(1);
        }
        $pic_path = $re_data['pic_path'];
        foreach($pic_path as $k=>$row){
            $size = $this->alido(4,$row);//获取图片大小
            $this->db->update('user_image',$size,['id'=>$re_data['id'][$k]]);
        }
        //$url = '/smg/gallery/upload_success/'.$re_data['all'].'/'.$re_data['success_num'];
        //echo "<script>window.location.href='".$url."'</script>";
        echo json_encode($re_data);
    }
    function  upload_success_bak(){
        $aid = $this->input->post('aid');$aid = $aid?$aid:0;
        $ids = $this->input->post('ids');$ids = $ids?$ids:0;
        //echo 'update '.$this->db->dbprefix.'image set aid='.$aid.' where id in ('.$ids.')';die;
        $this->db->query('update '.$this->db->dbprefix.'user_image set aid='.$aid.' where id in ('.$ids.') and user_id='.$this->user_id);
        $success = 0;
        if($this->db->affected_rows()>0){
            $success = 1;
        }
        $data = [
            'tpl'=>'smg/gallery_upload_success',
            'success'=>$success,
        ];
        $this->load->view('smg/home',$data);
    }

    /* 阿里百川 顽兔 图片操作  */
    function alido($case,$up_img,$name=NULL,$files = NULL){

        require_once('alimedia/alimage.class.php');

        $ak = '23302076';								// 用户的AK (user app key)
        $sk = 'bede9e60f311c9848a9c978b3581640f';		// 用户的SK (user secret key)
        $namespace = 'happylife';						// 空间名称  (user namespace)

        $aliImage  = new AlibabaImage($ak, $sk);		//设置AK和SK

        /*
         * 1.上传本地照片
         * 2.删除图片
         * 3.视频上传（商品视频）
         * 4.获取图片大小
         * */

        if($case==1){

            $uploadPolicy = new UploadPolicy( $namespace );	// 上传策略。并设置空间名
            $uploadOption = new UploadOption();
            $uploadOption->dir = $files;	// 文件路径，(可选，默认根目录"/")
            $uploadOption->name = $name;			// 文件名，(可选，不能包含"/"。若为空，则默认使用文件名)

            /*第三步：（必须）进行文件上传*/
            $re_data = $aliImage->upload( $up_img, $uploadPolicy, $uploadOption );

        }
        else if($case==2)
        {
            if($up_img==''){
                $re_data['success'] = 101;
                return $re_data;die;
            }

            $arr = explode('/',$up_img);
            $filename = end($arr);

            unset($arr[count($arr)-1]);
            unset($arr[0]);
            unset($arr[1]);
            unset($arr[2]);

            $dir = '';
            foreach($arr as $row){
                $dir.= '/'.$row;
            }

            $res = $aliImage->deleteFile($namespace, $dir, $filename );
            if($res['isSuccess']==true){
                $re_data['success'] = 101;
            }else{
                $re_data['success'] = 100;
            }

        }
        else if($case==3){
            $type = explode('.',$up_img['name']);
            $filename = md5(time().rand(1111,9999)).'.'.$type[1];

            /*第二步：（必须）在上传策略UploadPolicy中指定用户空间名。也可以根据需要设置其他参数*/
            $uploadPolicy = new UploadPolicy( $namespace );	// 上传策略。并设置空间名
            $uploadPolicy->dir = "/up/$files/".date('Ymd').'/';;	// 文件路径，(可选，默认根目录"/")
            $uploadPolicy->name = $filename;			// 文件名，(可选，不能包含"/"。若为空，则默认使用文件名)
            /*（可选）开发者可以在UploadOption中设置文件分片的大小（范围100K < size < 10M）。如果不指定分块大小，则为默认值2M。*/
            $uploadOption = new UploadOption();
            $uploadOption->blockSize = 1*1024*1024;		//设置分块大小为1M

            /*第三步：（必须）创建分片上传任务，完成初始化*/
            $filePath = $up_img['tmp_name'];
            $httpRes = $aliImage->multipartInit( $filePath, $uploadPolicy, $uploadOption );
            //return $httpRes;die;

            if( $httpRes ['isSuccess'] ) {
                /*第四步：（如果初始化成功）继续分片上传任务，完成文件上传*/
                $fileSize = filesize ( iconv('UTF-8','GB2312',$filePath) );		// 文件大小
                $blockSize = $uploadOption->blockSize;	// 文件分片大小
                $blockNum = intval ( ceil ( $fileSize / $blockSize ) ); // 文件分片后的块数
                for($i = 2; $i <= $blockNum; $i ++) {
                    $uploadOption->setPartNumber($i);	//待上传的文件块编号
                    $httpRes = $aliImage->multipartUpload( $filePath, $uploadPolicy, $uploadOption );
                    //var_dump($httpRes);
                    if ( !$httpRes ['isSuccess'] ) {
                        /*如果分片上传失败，则取消整个任务*/
                        //var_dump("文件块上传失败，立即取消分片上传任务");
                        $httpRes = $aliImage->multipartCancel( $uploadPolicy, $uploadOption );
                        //var_dump($httpRes);
                        $re_data['success'] = 100;
                        $re_data['error'] = '分片上传失败';
                        break;
                    }
                    if($i == $blockNum) {
                        /*如果上传最后一个文件块，则完成整个任务*/
                        $uploadOption->setMd5(md5_file ( iconv('UTF-8','GB2312',$filePath) ));
                        $httpRes = $aliImage->multipartComplete( $uploadPolicy, $uploadOption );
                        //return($httpRes);
                        $re_data['success'] = 101;
                        $re_data['message'] = $httpRes['url'];
                    }
                }
            }
        }
        else if($case==4){
            $img = explode('/',$up_img);
            $filename = $img[count($img)-1];
            unset($img[count($img)-1]);
            unset($img[0]);
            unset($img[1]);
            unset($img[2]);
            $dir = implode('/',$img);
            $res = $aliImage->getFileInfo($namespace, $dir, $filename);
            $re_data = ['height'=>$res['imageHeight'],'width'=>$res['imageWidth']];
        }

        return $re_data;
    }


}
