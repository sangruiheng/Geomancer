<?php

namespace Manage\Controller;

use Manage\Model\CustomerModel;
use Manage\Model\DomainlabelModel;
use Manage\Model\GeomancerModel;
use Think\Controller;

class CustomerController extends CommonController
{

    //客服列表
    public function customerList()
    {

        $customerModel = new CustomerModel();

        if (!empty($_GET['keyWord'])) {
            $map = $this->Search('user', $_GET['keyWord']);
        }
        $p = $_GET['p'];
        if (empty($p)) {
            $p = 1;
        }
        $customer = $customerModel->relation(array('userList', 'geomancerList'))->where($map)->order('id desc')->page($p . ',10')->select();
        foreach ($customer as &$value) {
            $value['count_user'] = count($value['userList']);
            $value['count_geomancer'] = count($value['geomancerList']);
            unset($value['userList']);
            unset($value['geomancerList']);
        }
        $count = $customerModel->where($map)->count();
        $Page = getpage($count, 10);
        foreach ($map as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $this->assign('page', $Page->show());
        $this->assign('list', $customer);
        $this->display();


    }


    //获取用户页面
    public function get_user()
    {
        $page_id = $_POST['page_id'];
        if(!$page_id){
            $page_id = 1;
        }
        $statr_page = ($page_id-1)*5;
        $page = 5;

        if (!empty($_POST['keyWord'])) {
            $map = $this->Search('user', $_POST['keyWord']);
        }

        $map['addTime'] = array('neq', '');
        $user = D('user')->where($map)->order('id desc')->limit($statr_page, $page)->select();
        foreach ($user as &$value) {
            $value['nickName'] = urldecode($value['nickName']);
        }

        if (!$user) {
            $this->ajaxReturn([
                'code' => 400,
                'msg' => 'error',
            ]);
        }
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'data' => $user
        ]);
    }

    //获取大师页面
    public function get_geomancer()
    {
        $page_id = $_POST['page_id'];
        if(!$page_id){
            $page_id = 1;
        }
        $statr_page = ($page_id-1)*5;
        $page = 5;

        $geomancerModel = new GeomancerModel();

        if (!empty($_POST['keyWord'])) {
            $map = $this->Search('geomancer', $_POST['keyWord']);
        }

        $geomancer = $geomancerModel->where($map)->order('id desc')->limit($statr_page, $page)->select();

        if (!$geomancer) {
            $this->ajaxReturn([
                'code' => 400,
                'msg' => 'error',
            ]);
        }
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'data' => $geomancer
        ]);
    }


    //添加编辑客服
    public function addCustomerData()
    {
        $backUrl = $_GET['backUrl'];
        $table = $_GET['table'];
        $controller = $_GET['controller'];
        $id = $_POST['id'];
        $sql = D($table);
        $customerModel = new CustomerModel();
        if ($sql->create()) {
            if (empty($id)) { //添加
                $sql->id = NULL;
                $result = $sql->add();
                $customerModel->addCsUser($result, $_POST['user_id']);
                $customerModel->addCsGeomancer($result, $_POST['geomancer_id']);
            } else {  //修改
                $result = $sql->save();
                $customerModel->editCsUser($id, $_POST['user_id']);
                $customerModel->editCsGeomancer($id, $_POST['geomancer_id']);
                $this->success('编辑成功！', U($controller . '/' . $backUrl));
            }
            if ($result) {
                $this->success('编辑成功！', U($controller . '/' . $backUrl));
            }
        } else {
            $this->error($sql->getError(), $jumpUrl = '', $ajax = true);
        }
    }


    //获取客服关联的用户和大师
    public function getCustomerUserAndGeomancer()
    {
        $customerModel = new CustomerModel();
        $customer_id = $_POST['customer_id'];
        $map['id'] = $customer_id;
        $customer = $customerModel->relation(array('userList', 'geomancerList'))->where($map)->order('id desc')->find();
        $arr_user = [];
        $arr_geomancer = [];
        foreach ($customer['userList'] as &$value) {
            $value['nickName'] = urldecode($value['nickName']);
            array_push($arr_user, $value['id']);
        }
        foreach ($customer['geomancerList'] as &$value) {
            array_push($arr_geomancer, $value['id']);
        }
        $customer['user_ids'] = implode(",", $arr_user);
        $customer['geomancer_ids'] = implode(",", $arr_geomancer);
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

    //获取客服关联的大师
    public function getCustomerGeomancerList()
    {
        $customerModel = new CustomerModel();
        $customer_id = $_GET['customer_id'];

        if (!empty($_GET['keyWord'])) {
            $map = $this->Search('user', $_GET['keyWord']);
        }
        $p = $_GET['p'];
        if (empty($p)) {
            $p = 1;
        }
        $map['id'] = $customer_id;

        $customer = $customerModel->relation(array('geomancerList'))->where($map)->order('id desc')->page($p . ',10')->find();
        $count = $customerModel->where($map)->count();
        $Page = getpage($count, 10);
        foreach ($map as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $this->assign('page', $Page->show());
        $this->assign('list', $customer['geomancerList']);
        $this->display();

    }


    //获取客服关联的用户
    public function getCustomerUserList()
    {
        $customerModel = new CustomerModel();
        $customer_id = $_GET['customer_id'];

        if (!empty($_GET['keyWord'])) {
            $map = $this->Search('user', $_GET['keyWord']);
        }
        $p = $_GET['p'];
        if (empty($p)) {
            $p = 1;
        }
        $map['id'] = $customer_id;

        $customer = $customerModel->relation(array('userList'))->where($map)->order('id desc')->page($p . ',10')->find();
        $count = $customerModel->where($map)->count();
        $Page = getpage($count, 10);
        foreach ($map as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $this->assign('page', $Page->show());
        $this->assign('list', $customer['userList']);
        $this->display();
    }


    public function testaaa()
    {
        $customerModel = new CustomerModel();
        $customer = $customerModel->relation(array('GustomerList'))->select();
        print_r($customer);
    }


    //删除  (无关联大师和用户的客服)
    public function deleteCustomer()
    {
        $table = $_POST['table'];
        $ids = $_POST['delID'];
        $sql = M($table);
        $customerModel = new CustomerModel();
        if (strlen($ids) > 0) {
            $ids = substr($ids, 0, strlen($ids) - 1);
        }
        $res = $this->checkCustomer($ids);
        if ($res) {
            $this->ajaxReturn([
                'code' => 200,
                'msg' => 'success',
            ]);
        } else {
            $this->ajaxReturn([
                'code' => 400,
                'msg' => '不能删除关联用户和大师的客服',
            ]);
        }

    }

    //检测客服是否有大师和用户
    protected function checkCustomer($ids)
    {
        $customerModel = new CustomerModel();
        $map['id'] = array('in', $ids);
        $customer = $customerModel->relation(array('userList', 'geomancerList'))->where($map)->select();
        foreach ($customer as $value) {
            if (!$value['userList'] && !$value['geomancerList']) {
                $customerModel->delete($value['id']);
            } else {
                return false;
            }
        }
        return true;
    }

}

?>