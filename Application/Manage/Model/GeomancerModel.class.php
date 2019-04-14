<?php

namespace Manage\Model;

use Think\Model\RelationModel;

class GeomancerModel extends RelationModel
{
    protected $_link = array(
        'domainList' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'class_name' => 'domainlabel',
            'foreign_key' => 'geomancer_id',
            'relation_foreign_key' => 'domainlabel_id',
            'relation_table' => 'icpnt_gedomain' //此处应显式定义中间表名称，且不能使用C函数读取表前缀
        ),
        'serviceList' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'class_name' => 'servicelabel',
            'foreign_key' => 'geomancer_id',
            'relation_foreign_key' => 'servicelabel_id',
            'relation_table' => 'icpnt_geservice' //此处应显式定义中间表名称，且不能使用C函数读取表前缀
        ),
        'customerList' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'class_name' => 'customer',
            'foreign_key' => 'geomancer_id',
            'relation_foreign_key' => 'customer_id',
            'relation_table' => 'icpnt_gecustomer' //此处应显式定义中间表名称，且不能使用C函数读取表前缀
        ),
    );

    protected $_validate = array();


    //添加大师关联的领域标签
    public function addDoMainLabel($geomancer_id, $domainlabelIDS)
    {
        if ($domainlabelIDS) {
            $gedomainModel = new GedomainModel();
            $arr_domainlabelID = explode(",", $domainlabelIDS);
            foreach ($arr_domainlabelID as $key => $value) {
                $gedomainModel->geomancer_id = $geomancer_id;
                $gedomainModel->domainlabel_id = $value;
                $gedomainModel->add();
            }
        }
    }

    //修改大师关联的领域标签
    public function editDoMainLabel($geomancer_id, $domainlabelIDS)
    {
        $gedomainModel = new GedomainModel();
        $arr_domainlabelID = explode(",", $domainlabelIDS);
        $map['geomancer_id'] = $geomancer_id;
        //123 4  shujuku
        //134  edit
        //增加
        $gedomain = $gedomainModel->where($map)->getField('domainlabel_id', true);
        if($arr_domainlabelID != $gedomain){
            foreach ($arr_domainlabelID as $value){
                if(!in_array($value,$gedomain)){
                    $gedomainModel->geomancer_id = $geomancer_id;
                    $gedomainModel->domainlabel_id = $value;
                    $gedomainModel->add();
                }
            }
        }

        //删除
        $gedomain = $gedomainModel->where($map)->getField('domainlabel_id', true);
        if($arr_domainlabelID != $gedomain){
            foreach ($gedomain as $value){
                if(!in_array($value,$arr_domainlabelID)){
                    $where['domainlabel_id'] = $value;
                    $where['geomancer_id'] = $geomancer_id;
                    $gedomainModel->where($where)->delete();
                }
            }
        }

    }


    //添加大师关联的服务标签
    public function addServiceLabel($geomancer_id, $servicelabelIDS)
    {
        if ($servicelabelIDS) {
            $geserviceModel = new GeserviceModel();
            $arr_servicelabelID = explode(",", $servicelabelIDS);
            foreach ($arr_servicelabelID as $key => $value) {
                $geserviceModel->geomancer_id = $geomancer_id;
                $geserviceModel->servicelabel_id = $value;
                $geserviceModel->add();
            }
        }
    }

    //修改大师关联的服务标签
    public function editServiceLabel($geomancer_id, $servicelabelIDS)
    {
        $geserviceModel = new GeserviceModel();

        $arr_servicelabelID = explode(",", $servicelabelIDS);
        $map['geomancer_id'] = $geomancer_id;
        //123 4  shujuku
        //134  edit
        //增加
        $geservice = $geserviceModel->where($map)->getField('servicelabel_id', true);
        if($arr_servicelabelID != $geservice){
            foreach ($arr_servicelabelID as $value){
                if(!in_array($value,$geservice)){
                    $geserviceModel->geomancer_id = $geomancer_id;
                    $geserviceModel->servicelabel_id = $value;
                    $geserviceModel->add();
                }
            }
        }

        //删除
        $geservice = $geserviceModel->where($map)->getField('servicelabel_id', true);

        if($arr_servicelabelID != $geservice){
            foreach ($geservice as $value){
                if(!in_array($value,$arr_servicelabelID)){
                    $where['servicelabel_id'] = $value;
                    $where['geomancer_id'] = $geomancer_id;
                    $geserviceModel->where($where)->delete();
                }
            }
        }

    }



    //添加大师关联的客服
    public function addCustomer($geomancer_id, $customerIDS)
    {
        if ($customerIDS) {
            $GecustomerModel = new GecustomerModel();
            $arr_customerID = explode(",", $customerIDS);
            foreach ($arr_customerID as $key => $value) {
                $GecustomerModel->geomancer_id = $geomancer_id;
                $GecustomerModel->customer_id = $value;
                $GecustomerModel->add();
            }
        }
    }


    //修改大师关联的客服
    public function editCustomer($geomancer_id, $customerIDS)
    {
        $gecustomerModel = new GecustomerModel();

        $arr_customerID = explode(",", $customerIDS);
        $map['geomancer_id'] = $geomancer_id;
        //123 4  shujuku
        //134  edit
        //增加
        $gecustomer = $gecustomerModel->where($map)->getField('customer_id', true);
        if($arr_customerID != $gecustomer){
            foreach ($arr_customerID as $value){
                if(!in_array($value,$gecustomer)){
                    $gecustomerModel->geomancer_id = $geomancer_id;
                    $gecustomerModel->customer_id = $value;
                    $gecustomerModel->add();
                }
            }
        }

        //删除
        $gecustomer = $gecustomerModel->where($map)->getField('customer_id', true);

        if($arr_customerID != $gecustomer){
            foreach ($gecustomer as $value){
                if(!in_array($value,$arr_customerID)){
                    $where['customer_id'] = $value;
                    $where['geomancer_id'] = $geomancer_id;
                    $gecustomerModel->where($where)->delete();
                }
            }
        }

    }


}