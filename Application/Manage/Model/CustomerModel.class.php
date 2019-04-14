<?php

namespace Manage\Model;

use Think\Model\RelationModel;

class CustomerModel extends RelationModel
{
    protected $_link = array(

        'userList' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'class_name' => 'user',
            'foreign_key' => 'customer_id',
            'relation_foreign_key' => 'user_id',
            'relation_table' => 'icpnt_csuser' //此处应显式定义中间表名称，且不能使用C函数读取表前缀
        ),
        'geomancerList' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'class_name' => 'geomancer',
            'foreign_key' => 'customer_id',
            'relation_foreign_key' => 'geomancer_id',
            'relation_table' => 'icpnt_csgeomancer' //此处应显式定义中间表名称，且不能使用C函数读取表前缀
        ),

    );

    protected $_validate = array(
        array('customer_name', 'require', '标题不能为空'),
        array('customer_wechat', 'require', '微信不能为空'),
        array('customer_tel', 'require', '手机号不能为空'),
    );


    //添加客服关联的用户
    public function addCsUser($customer_id, $userIDS)
    {
        if ($userIDS) {
            $csUserModel = new CsuserModel();
            $arr_userID = explode(",", $userIDS);
            foreach ($arr_userID as $key => $value) {
                $csUserModel->customer_id = $customer_id;
                $csUserModel->user_id = $value;
                $csUserModel->add();
            }
        }
    }


    //修改大师关联的领域标签
    public function editCsUser($customer_id, $userIDS)
    {
        $csUserModel = new CsuserModel();

        $arr_userID = explode(",", $userIDS);
        $map['customer_id'] = $customer_id;
        //123 4  shujuku
        //134  edit
        //增加
        $customer = $csUserModel->where($map)->getField('user_id', true);
        if ($arr_userID != $customer) {
            foreach ($arr_userID as $value) {
                if (!in_array($value, $customer)) {
                    $csUserModel->customer_id = $customer_id;
                    $csUserModel->user_id = $value;
                    $csUserModel->add();
                }
            }
        }

        //删除
        $customer = $csUserModel->where($map)->getField('user_id', true);
        if ($arr_userID != $customer) {
            foreach ($customer as $value) {
                if (!in_array($value, $arr_userID)) {
                    $where['user_id'] = $value;
                    $where['customer_id'] = $customer_id;
                    $csUserModel->where($where)->delete();
                }
            }
        }

    }


    //添加客服关联的大师
    public function addCsGeomancer($customer_id, $geomancerIDS)
    {
        if ($geomancerIDS) {
            $csGeomancerModel = new CsgeomancerModel();
            $arr_geomancerID = explode(",", $geomancerIDS);
            foreach ($arr_geomancerID as $key => $value) {
                $csGeomancerModel->customer_id = $customer_id;
                $csGeomancerModel->geomancer_id = $value;
                $csGeomancerModel->add();
            }
        }
    }


    //修改客服关联的大师
    public function editCsGeomancer($customer_id, $geomancerIDS)
    {

        $csGeomancerModel = new CsgeomancerModel();

        $arr_geomancerID = explode(",", $geomancerIDS);
        $map['customer_id'] = $customer_id;
        //123 4  shujuku
        //134  edit
        //增加
        $csGeomancer = $csGeomancerModel->where($map)->getField('geomancer_id', true);
        if ($arr_geomancerID != $csGeomancer) {
            foreach ($arr_geomancerID as $value) {
                if (!in_array($value, $csGeomancer)) {
                    $csGeomancerModel->customer_id = $customer_id;
                    $csGeomancerModel->geomancer_id = $value;
                    $csGeomancerModel->add();
                }
            }
        }

        //删除
        $csGeomancer = $csGeomancerModel->where($map)->getField('geomancer_id', true);
        if ($arr_geomancerID != $csGeomancer) {
            foreach ($csGeomancer as $value) {
                if (!in_array($value, $arr_geomancerID)) {
                    $where['geomancer_id'] = $value;
                    $where['customer_id'] = $customer_id;
                    $csGeomancerModel->where($where)->delete();
                }
            }
        }

    }


    //判断两个数组是否相同
    protected function is_match($arr1, $arr2)
    {
        if ($arr1 != $arr2) {
            return false;
        }
        return true;
    }


}