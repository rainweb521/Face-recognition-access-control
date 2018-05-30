<?php
namespace app\index\controller;
use app\config\model\Ai;
use app\config\model\StudentM;
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

class Index extends Common {

    public function index(){
//        $fun = new \AipOcr($this->Ocr_APP_ID, $this->Ocr_API_KEY, $this->Ocr_SECRET_KEY);
//        $result = $fun->vehicleLicense(file_get_contents('./public/1.jpg'));
//        var_dump($result);
        return \view('student/index');
//        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    public function door(){
//        $i=9;$j=10;
//        echo (++$i)+$j;
//        exit();
        return \view('door');
    }
    /**
     *用于上传头像采集图片的ajax，目录与普通图片不同
     */
    public function image_ajax(){
        $image_src = upload_photo('the_file');
        return $image_src;
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
        $scoure = 0;
        /** state = 0 表示没有识别到人脸，  1 表示无人脸  2表示通过 */
        if ($result['error_code']==0){
            $scoure = floor($result['result']['user_list'][0]['score']);
            if ($scoure>80){
                $state = 2;
                $user_id = $result['result']['user_list'][0]['user_id'];
                $stuent_model = new StudentM();
                $user = $stuent_model->get_Info(['user_id'=>$user_id]);
                $image2 = $user['photo'];
            }else{
                $state = 1;
            }
//            foreach ($result['result']['user_list'] as $line){
//
//            }
        }
        $result = array('state'=>$state,'image1'=>$image1,'image2'=>$image2,'scoure'=>$scoure);
        return $result;
//        return '{"error_code":222202,"error_msg":"pic not has face","log_id":2659027871,"timestamp":1527231061,"cached":0,"result":null}';
    }

}
