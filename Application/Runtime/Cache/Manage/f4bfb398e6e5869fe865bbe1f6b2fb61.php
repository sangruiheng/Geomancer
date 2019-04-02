<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>无标题文档</title>
    <!--公共css-->
<link rel="stylesheet" type="text/css" href="/Public/Manage/tools/bootstrap-3.2.0-dist/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/Public/Manage/css/fontIcon/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" href="/Public/Manage/css/common/common.css" />
<link rel="stylesheet" type="text/css" href="/Public/Manage/css/alert.css" />
<link rel="stylesheet" type="text/css" href="/Public/Manage/tools/iCheck/flat/blue.css" />
<link rel="stylesheet" type="text/css" href="/Public/Manage/js/tools/icpntDialog/css/icpntDialog.css" />
<!--公共js-->
<script type="text/javascript" src="/Public/Manage/js/common/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="/Public/Manage/js/common/jquery.form.js"></script>
<script type="text/javascript" src="/Public/Manage/tools/bootstrap-3.2.0-dist/js/bootstrap.js"></script>
<script type="text/javascript" src="/Public/Manage/tools/Validform_v5.3.2/Validform_v5.3.2_min.js"></script>
<script type="text/javascript" src="/Public/Manage/js/tools/icpntDialog/js/icpntDialog.js"></script>
<script type="text/javascript" src="/Public/Manage/tools/iCheck/icheck.js"></script>
<script type="text/javascript" src="/Public/Manage/js/common/common.js"></script>
<script type="text/javascript" src="/Public/Manage/postInfo/postInfo.js"></script>
<script>
//定义ThinkPHP模板常量，方便在js中使用
var APP = '/manage.php';
var PUBLIC = '/Public/Manage';
var URL = '/manage.php/User';
var CONTROLLER_NAME = '<?php echo (CONTROLLER_NAME); ?>';
var ACTION_NAME = '<?php echo (ACTION_NAME); ?>';
var GROUPID = '<?php echo ($_SESSION['crm_rules']); ?>';
var AUTH_ADD_ID = '<?php echo C('AUTH_MODULE.auth_add_id');?>';
var AUTH_DEL_ID = '<?php echo C('AUTH_MODULE.auth_del_id');?>';
var AUTH_SAVE_ID = '<?php echo C('AUTH_MODULE.auth_save_id');?>';
var AUTH_USER_ID = '<?php echo C('AUTH_MODULE.auth_user_id');?>';
var AUTH_GROUP_ID = '<?php echo C('AUTH_MODULE.auth_group_id');?>';
var PAGE = '<?php echo ($_GET['p']); ?>';
var KEYWORD = '<?php echo ($_GET['keyWord']); ?>';
$(document).ready(function(e) {
    $('input[data-name=multi-select]').iCheck({
		checkboxClass: 'icheckbox_flat-blue',
		radioClass: 'iradio_flat-blue'
	});
	
});
</script>
    <link rel="stylesheet" type="text/css" href="/Public/Manage/css/common/rightCommon.css" />
</head>

<body>
<!--alert弹窗Start  -->
<div id="top-alert" class="fixed alert alert-error" style="display:none;">
    <button class="close fixed" style="margin-top: 4px;">&times;</button>
    <div class="alert-content">这是内容</div>
</div>
<!--alert弹窗end  -->
<nav class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
        <a class="navbar-brand" href="#">用户管理</a>
    </div>
    <div>
        <form class="navbar-form navbar-right listSearch" role="search" method="get" action="/manage.php/User/userList">
            <div class="form-group">
                <input name="keyWord" type="text" class="form-control" id="keyWord" placeholder="请输入关键词进行搜索">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> 搜索</button>
        </form>
    </div>
</nav>

<div class="list-box">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>选择</th>
            <th>编号</th>
            <th>昵称</th>
            <th>头像</th>
            <th>手机号</th>
            <th>地址</th>
            <th>城市</th>
            <th>注册时间</th>
            <th>最后登陆时间</th>
            <!--<th>操作(是否推荐到首页)</th>-->
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><input type="checkbox" name="del_listID" id="del_listID" data-name="multi-select"
                           value="<?php echo ($vo["id"]); ?>"/></td>
                <td><?php echo ($vo["id"]); ?></td>
                <td><?php echo ($vo["nickName"]); ?></td>
                <td>
                    <?php if($vo['avatarUrl']): ?><img src="<?php echo ($vo["avatarUrl"]); ?>" width="70px" height="70px" alt="暂无用户头像"/>
                        <?php else: endif; ?>
                </td>
                <td><?php echo ($vo["wx_phone"]); ?></td>
                <td><?php echo ($vo["province"]); ?></td>
                <td><?php echo ($vo["city"]); ?></td>
                <td><?php echo (date("Y-m-d H:i:s",$vo["addTime"])); ?></td>
                <td><?php echo (date("Y-m-d H:i:s",$vo["lastTime"])); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
</div>


<ul class="pagination">
    <?php echo ($page); ?>
</ul>
<script src="/Public/Manage/js/status/status.js"></script>
</body>
</html>