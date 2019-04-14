<?php

namespace Manage\Controller;

use Manage\Model\CityModel;
use Manage\Model\CustomerModel;
use Manage\Model\DomainlabelModel;
use Manage\Model\GeomancerModel;
use Manage\Model\ServicelabelModel;
use Think\Controller;

class GeomancerController extends CommonController
{

    //大师列表
    public function geomancerList()
    {
        $geomancerModel = new GeomancerModel();
        if (!empty($_GET['keyWord'])) {
            $map = $this->Search('user', $_GET['keyWord']);
        }
        $p = $_GET['p'];
        if (empty($p)) {
            $p = 1;
        }
        $geomancer = $geomancerModel->where($map)->order('id desc')->page($p . ',10')->select();
        $count = $geomancerModel->where($map)->count();
        $Page = getpage($count, 10);
        foreach ($map as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $this->assign('page', $Page->show());
        $this->assign('list', $geomancer);
        $this->display();


    }

    //添加编辑大师
    public function addGeomancerData()
    {
        $backUrl = $_GET['backUrl'];
        $table = $_GET['table'];
        $controller = $_GET['controller'];
        $id = $_POST['id'];
        $geomancerModel = new GeomancerModel();
        $sql = D($table);
        if ($sql->create()) {
            if (empty($id)) { //添加
                $sql->id = NULL;
                $result = $sql->add();
                $geomancerModel->addDoMainLabel($result, $_POST['domainlabel_id']);
                $geomancerModel->addServiceLabel($result, $_POST['servicelabel_id']);
                $geomancerModel->addCustomer($result, $_POST['customer_id']);
            } else {  //修改
//                $result = $sql->save();
                $geomancerModel->editDoMainLabel($id, $_POST['domainlabel_id']);
                $geomancerModel->editServiceLabel($id, $_POST['servicelabel_id']);
                $geomancerModel->editCustomer($id, $_POST['customer_id']);
            }
            if ($result) {
                $this->success('编辑成功！', U($controller . '/' . $backUrl));
            }
        } else {
            $this->error($sql->getError(), $jumpUrl = '', $ajax = true);
        }
    }

    //获取领域标签
    public function get_domainlabel()
    {
        $domainLabelModel = new DomainlabelModel();

        $page_id = $_POST['page_id'];
        if (!$page_id) {
            $page_id = 1;
        }
        $statr_page = ($page_id - 1) * 5;
        $page = 5;
        if (!empty($_POST['keyWord'])) {
            $map = $this->Search('domainlabel', $_POST['keyWord']);
        }

        $domainLabel = $domainLabelModel->where($map)->order('id desc')->limit($statr_page, $page)->select();
        foreach ($domainLabel as &$value) {
            $value['domainlabel_img'] = C('Web.img_prefix') . $value['domainlabel_img'];
        }
        if (!$domainLabel) {
            $this->ajaxReturn([
                'code' => 400,
                'msg' => 'error',
            ]);
        }
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'data' => $domainLabel
        ]);

    }

    //获取服务标签
    public function get_servicelabel()
    {
        $serviceLabelModel = new ServicelabelModel();

        $page_id = $_POST['page_id'];
        if (!$page_id) {
            $page_id = 1;
        }
        $statr_page = ($page_id - 1) * 5;
        $page = 5;

        if (!empty($_POST['keyWord'])) {
            $map = $this->Search('servicelabel', $_POST['keyWord']);
        }

        $serviceLabel = $serviceLabelModel->where($map)->order('id desc')->limit($statr_page, $page)->select();
        foreach ($serviceLabel as &$value) {
            $value['servicelabel_img'] = C('Web.img_prefix') . $value['servicelabel_img'];
        }

        if (!$serviceLabel) {
            $this->ajaxReturn([
                'code' => 400,
                'msg' => 'error',
            ]);
        }
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'data' => $serviceLabel
        ]);

    }

    //获取客服
    public function get_customer()
    {
        $customerModel = new CustomerModel();

        $page_id = $_POST['page_id'];
        if (!$page_id) {
            $page_id = 1;
        }
        $statr_page = ($page_id - 1) * 5;
        $page = 5;

        if (!empty($_POST['keyWord'])) {
            $map = $this->Search('customer', $_POST['keyWord']);
        }
        $customer = $customerModel->relation(array('userList', 'geomancerList'))->where($map)->order('id desc')->limit($statr_page, $page)->select();
        foreach ($customer as &$value) {
            $value['count_user'] = count($value['userList']);
            $value['count_geomancer'] = count($value['geomancerList']);
            unset($value['userList']);
            unset($value['geomancerList']);
        }

        if (!$customer) {
            $this->ajaxReturn([
                'code' => 400,
                'msg' => 'error',
            ]);
        }
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'data' => $customer
        ]);

    }

    //获取省份
    public function getProvince()
    {
        $cityModel = new CityModel();
        $map['pid'] = 0;
        $province = $cityModel->where($map)->select();
        if (!$province) {
            $this->ajaxReturn([
                'code' => 400,
                'msg' => 'error',
            ]);
        }
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'data' => $province
        ]);
    }

    //根据省份获取市
    public function getCity()
    {
        $cityModel = new CityModel();
        $province_id = $_POST['province_id'];
        $map['pid'] = $province_id;
        $city = $cityModel->where($map)->select();
        if (!$city) {
            $this->ajaxReturn([
                'code' => 400,
                'msg' => 'error',
            ]);
        }
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'data' => $city
        ]);
    }

    //根据市获取区
    public function getCounty()
    {
        $cityModel = new CityModel();
        $county_id = $_POST['county_id'];
        $map['pid'] = $county_id;
        $county = $cityModel->where($map)->select();
        if (!$county) {
            $this->ajaxReturn([
                'code' => 400,
                'msg' => 'error',
            ]);
        }
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'data' => $county
        ]);
    }


    //修改大师显示信息
    public function getEditGeomancerInfo()
    {
        $geomancerModel = new GeomancerModel();
        $geomancer_id = $_POST['geomancer_id'];
        $map['id'] = $geomancer_id;
        $domainLable = $geomancerModel->relation(array('domainList', 'serviceList', 'customerList'))->where($map)->order('id desc')->find();
        $arr_domainLable = [];
        foreach ($domainLable['domainList'] as &$value) {
            array_push($arr_domainLable, $value['id']);
        }
        $domainLable['domainLable_ids'] = implode(",", $arr_domainLable);
        if (!$domainLable) {
            $this->ajaxReturn([
                'code' => 400,
                'msg' => 'error',
            ]);
        }
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'data' => $domainLable
        ]);
    }

    public function getDomainLabel()
    {
        $domainLabelModel = new DomainlabelModel();
        $domainlabel_ids = $_POST['domainlabel_ids'];
        $map['id'] = array('in', $domainlabel_ids);
        $domainLabel = $domainLabelModel->where($map)->select();
        if (!$domainLabel) {
            $this->ajaxReturn([
                'code' => 400,
                'msg' => 'error',
            ]);
        }
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'data' => $domainLabel
        ]);
    }


}

?>