<?php
namespace app\index\controller;
use app\config\model\Ai;
use think\Controller;
use \think\Request;
use \think\View;
use app\config\model\Use_ai;
vendor('baidu.AipOcr');
vendor('baidu.AipFace');
vendor('baidu.AipImageClassify');
vendor('baidu.AipNlp');
vendor('baidu.AipSpeech');
vendor('baidu.image');
vendor('Qiniu.autoload');
use Qiniu\Auth as Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class Demo extends Common {

    public function index(){
//        $fun = new \AipOcr($this->Ocr_APP_ID, $this->Ocr_API_KEY, $this->Ocr_SECRET_KEY);
//        $result = $fun->vehicleLicense(file_get_contents('./public/1.jpg'));
//        var_dump($result);
        return \view('index');
//        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    public function ajax(){
//        $flag = Request::instance()->post('the_file',0);
        $image_src = upload_image('the_file');
        $client = new \AipFace($this->Face_APP_ID, $this->Face_API_KEY, $this->Face_SECRET_KEY);
        // 调用人脸检测
        $option = array(
            'max_face_num' => 5,
            'face_field' => 'expression,beauty,faceshape,facetype,gender,glasses,age,race,qualities'
        );
        $imageType = "BASE64";
//        $result = $client->detect($flag,$imageType,$option);
//        $result = $client->addUser(base64_encode(file_get_contents('.'.$image_src)),$imageType,'imnu','1122');
        $result = $client->detect(base64_encode(file_get_contents('.'.$image_src)),$imageType,$option);
        return $result['result']['face_list'][0];
    }
    public function face_detect(){
        $useai_model = new Use_ai();
        $flag = Request::instance()->post('flag',0);
        $tip = '0';
        $type = 1;
        $field = 'face1';
        $view = 'face_detect';
        $num = use_num($type);
        if ($flag==0){
            return \view($view,array('num'=>$num,'tip'=>$tip));
        }else{
            $image_src = upload_image('image');
//            echo $image_src;exit();
            if ($image_src=='0'){
                $tip = '2';
                return \view($view,array('num'=>$num,'tip'=>$tip));
            }else{
                $client = new \AipFace($this->Face_APP_ID, $this->Face_API_KEY, $this->Face_SECRET_KEY);
                // 调用人脸检测
                $option = array(
                    'max_face_num' => 5,
                    'face_field' => 'expression,beauty,faceshape,facetype,gender,glasses,age,race,qualities'
                );
                $imageType = "BASE64";
//                $base64_file = $this->base64EncodeImage('.'.$image_src);
                $result = $client->detect(base64_encode(file_get_contents('.'.$image_src)),$imageType,$option);
                $result = $client->addUser(base64_encode(file_get_contents('.'.$image_src)),$imageType,$option);
//                return ($result);
//                exit();
                $log = '人脸检测';
                $useai_model->reduce_UseaiNum($field);
                $data['ip'] = $_SERVER['REMOTE_ADDR'];
                $data['image1'] = $image_src;
                $data['image2'] = '';
                $data['status'] = $log;
                $data['state'] = 1;/** 图片为1 文字为2 语音为3 */
                $ai_model = new Ai();
                $ai_model->insert_AiInfo($data);
                $num = use_num($type);
                $tip = '1';
                return \view($view,array('num'=>$num,'tip'=>$tip,'result'=>$result,'image_src'=>$image_src));
            }
        }
    }


}
