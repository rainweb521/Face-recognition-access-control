<?php
/**
 * Created by PhpStorm.
 * User: Rain
 * Date: 2017/10/24
 * Time: 18:01
 */
namespace app\config\model;
use think\Model;

class StudentM extends Model{
    /**
     * 主键默认自动识别
     */
//    protected $pk = 'uid';
// 设置当前模型对应的完整数据表名称
    protected $table = 'student';
    public function get_Info($where=null){
        $data = StudentM::where($where)->find();
        if ($data!=null){
            return $data->getData();
        }else{
            return $data;
        }
    }

    public function insert_Info($data){
        StudentM::save($data);
    }
    public function delete_Info($where){
        StudentM::where($where)->delete();
    }
    public function get_List($where=null){
        $list = StudentM::where($where)->select();
        return $list;
    }
    public function get_UsePageList($where=null,$begin,$num){
        $list = StudentM::where($where)->limit($begin,$num)->select();
        return $list;
    }
    public function get_PageList($page=1){
        $page_num = 10;
        $list_num = 3;
        $page_list = array();
        $all_num = StudentM::count('id');
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