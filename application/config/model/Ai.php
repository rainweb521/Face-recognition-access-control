<?php
/**
 * Created by PhpStorm.
 * User: Rain
 * Date: 2017/10/24
 * Time: 18:01
 */
namespace app\config\model;
use phpDocumentor\Reflection\Types\Null_;
use think\Model;

class Ai extends Model{
    /**
     * 主键默认自动识别
     */
//    protected $pk = 'uid';
// 设置当前模型对应的完整数据表名称
    protected $table = 'ai_use';
    public function get_UserInfo($where=null){
        $data = Ai::where($where)->find();
        if ($data!=null){
            return $data->getData();
        }else{
            return $data;
        }
    }
    /** 查询所得到的IP地址在数组库中是否存在，为了提高效率，暂时不用了，因为大部分IP肯定是不同的
     * 只针对127.0.0.1进行识别就可以了
     * @param $ip
     * @return mixed
     */
    private function ip_databases_select($ip){
        if ($ip=='127.0.0.1'){
            return '127.0.0.1	保留地址';
        }else{
            return ip_select($ip);
        }
//        $con = $this->conn_mysql();  //连接mysql
//        $result = mysqli_query($con,'SELECT address FROM rain_ai WHERE ip="'.$ip.'"'); //进行查询操作
//        $row = mysqli_fetch_array($result);  //获取查询到的数组
//        $this->close_mysql($con);   //释放连接
//        if (empty($row['address'])){
//            return $this->ip_select($ip);
//        }else{
//            return $row['address'];
//        }
    }
    public function insert_AiInfo($data){
        $data['add_time'] = date('Y-m-d:h:i:sa');
        $data['address'] = $this->ip_databases_select($data['ip']);;
        Ai::save($data);
    }
    public function save_AiInfo($data,$where){
        Ai::save($data,$where);
    }
    public function get_UserList($where=null){
        $list = Ai::where($where)->select();
        return $list;
    }
    public function get_UsePageList($where=null,$begin,$num){
        $list = Ai::where($where)->limit($begin,$num)->select();
        return $list;
    }
    public function get_PageList($page=1){
        $page_num = 10;
        $list_num = 3;
        $page_list = array();
        $all_num = AI::count('id');
        $all_page = intval($all_num/$page_num);
        /** 防止不足一页时，剩下的不显示 */
        if (($all_page*$page_num)<$all_num){$all_page = $all_page + 1;}
//        array('num'=>$page-2,'show'=>$page-2,'active'=>'');
        array_push($page_list,array('num'=>1,'show'=>'«','active'=>''));
        if (($page-2)>=1){array_push($page_list,array('num'=>$page-2,'show'=>$page-2,'active'=>''));}
        if (($page-1)>=1){array_push($page_list,array('num'=>$page-1,'show'=>$page-1,'active'=>''));}
        array_push($page_list,array('num'=>$page,'show'=>$page,'active'=>'am-active'));
        if (($page+1)<=$all_page){array_push($page_list,array('num'=>$page+1,'show'=>$page+1,'active'=>''));}
        if (($page+2)<=$all_page){array_push($page_list,array('num'=>$page+2,'show'=>$page+2,'active'=>''));}
        array_push($page_list,array('num'=>$all_page,'show'=>'»','active'=>''));
        return $page_list;
    }

}