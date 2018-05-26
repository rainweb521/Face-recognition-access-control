<?php
namespace app\index\controller;
use app\config\model\StoreM;
use app\config\model\StudentM;
use think\Controller;
use \think\Request;
use \think\View;
vendor('baidu.AipOcr');
vendor('baidu.AipFace');
vendor('baidu.AipImageClassify');
vendor('baidu.AipNlp');
vendor('baidu.AipSpeech');
vendor('baidu.image');

class Student extends Common {

    public function index(){
        $student_model = new StudentM();
        $data = $student_model->get_List();
        return \view('index',array('data'=>$data));
    }
    public function register(){
        $flag = Request::instance()->post('flag',0);
        if ($flag==1){
            $data['name'] = Request::instance()->post('name','');
            $data['number'] = Request::instance()->post('number','');
            $data['college'] = Request::instance()->post('college','');
            $data['grade'] = Request::instance()->post('grade','');
            $data['photo'] = Request::instance()->post('image_src','');
            $data['add_time'] = date('Y-m-d');
            $data['status'] = 1;
            $data['state'] = 1;
            $data['type'] = 1;
            $data['group_id'] = 'imnu';
            $data['user_id'] = md5($data['name'].$data['number'].date('Y-m-d h:m:i'));
//            var_dump($data);exit();
            $student_model = new StudentM();
            $student_model->insert_Info($data);
            $client = new \AipFace($this->Face_APP_ID, $this->Face_API_KEY, $this->Face_SECRET_KEY);
            $imageType = "BASE64";
            $result = $client->addUser(base64_encode(file_get_contents('.'.$data['photo'])),$imageType,$data['group_id'],$data['user_id']);
            /**
             *以后考虑，人脸在人脸库中是否重复，学号是否重复
             *
             */
            $this->success('人脸注册成功','/index.php/index/student/index');
        }
        return \view('register');
    }
    public function door_ajax(){
        $image_src = upload_image('the_file');
        $client = new \AipFace($this->Face_APP_ID, $this->Face_API_KEY, $this->Face_SECRET_KEY);

        $imageType = "BASE64";
//        $result = $client->detect($flag,$imageType,$option);
//        $result = $client->search(base64_encode(file_get_contents('./public/uploads/20180525/3e5615cfb53c4913222dd1fa4fbec9ee.png')),$imageType,'imnu');
        $result = $client->search(base64_encode(file_get_contents('.'.$image_src)),$imageType,'imnu');
//        $result = $client->detect(base64_encode(file_get_contents('.'.$image_src)),$imageType,$option);

        $image1 = $image_src;
        $image2 = '';
        $state = 0;
        /** state = 0 表示没有识别到人脸，  1 表示无人脸  2表示通过 */
        if ($result['error_code']==0){
            $scoure = $result['result']['user_list'][0]['score'];
            if ($scoure>60){
                $state = 2;
            }else{
                $state = 1;
            }
//            foreach ($result['result']['user_list'] as $line){
//
//            }
        }
        $result = array('state'=>$state,'image1'=>$image1,'image2'=>$image2);
        return $result;
//        return '{"error_code":222202,"error_msg":"pic not has face","log_id":2659027871,"timestamp":1527231061,"cached":0,"result":null}';
    }

}
