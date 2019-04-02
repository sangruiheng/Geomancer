<?php
return array(
    'URL_ROUTER_ON' => true, //开启路由
    'URL_PATHINFO_DEPR' => '/', //PATHINFO URL分割符
    'URL_ROUTE_RULES' => array( //定义路由规则
        'api/getBanner' => array('Api/Banner/getBanner', array('method' => 'get')),     //获取banner


        'api/Scategory/getListenScategory' => array('Api/Scategory/getListenScategory', array('method' => 'get')),     //获取听故事分类
        'api/Scategory/getTellingScategory' => array('Api/Scategory/getTellingScategory', array('method' => 'get')),     //获取讲故事分类


        'api/Salbum/listenIDSort' => array('Api/Salbum/listenIDSort', array('method' => 'post')),         //专辑根据综合排序(听)
        'api/Salbum/getListenSalbum' => array('Api/Salbum/getListenSalbum', array('method' => 'get')),         //根据分类获取专辑(听)
        'api/Salbum/listenPlayVolumeSort' => array('Api/Salbum/listenPlayVolumeSort', array('method' => 'post')),         //专辑根据播放量排序(听)
        'api/Salbum/listenNewestSort' => array('Api/Salbum/listenNewestSort', array('method' => 'post')),         //专辑根据最新排序(听)
        'api/Salbum/listenDurationSort' => array('Api/Salbum/listenDurationSort', array('method' => 'post')),         //专辑根据时长排序(听)
        'api/Salbum/salbumFreeList' => array('Api/Salbum/salbumFreeList', array('method' => 'get')),         //免费榜(听)
        'api/Salbum/salbumChargeList' => array('Api/Salbum/salbumChargeList', array('method' => 'get')),         //收费榜(听)


        'api/Tellingstory/getBgmAndMcategory' => array('Api/Tellingstory/getBgmAndMcategory', array('method' => 'get')),         //背景音乐及分类(讲)
        'api/Tellingstory/likeBgm' => array('Api/Tellingstory/likeBgm', array('method' => 'post')),         //收藏背景音乐
        'api/Tellingstory/cancelLikeBgm' => array('Api/Tellingstory/cancelLikeBgm', array('method' => 'post')),         //取消收藏背景音乐
        'api/Tellingstory/searchBgm' => array('Api/Tellingstory/searchBgm', array('method' => 'post')),         //搜索音乐(讲)
        'api/Tellingstory/loadMoreBgm' => array('Api/Tellingstory/loadMoreBgm', array('method' => 'post')),         //下拉加载更多歌曲
        'api/Tellingstory/getSearchTellingStory' => array('Api/Tellingstory/getSearchTellingStory', array('method' => 'post')),         //搜索文章作家(讲)
        'api/Tellingstory/tellingIDSort' => array('Api/Tellingstory/tellingIDSort', array('method' => 'post')),         //故事根据综合排序(讲)
        'api/Tellingstory/tellingPlayVolumeSort' => array('Api/Tellingstory/tellingPlayVolumeSort', array('method' => 'post')),         //故事根据播放量排序(讲)
        'api/Tellingstory/tellingNewestSort' => array('Api/Tellingstory/tellingNewestSort', array('method' => 'post')),         //故事根据最新排序(讲)
        'api/Tellingstory/getTellingStoryContent' => array('Api/Tellingstory/getTellingStoryContent', array('method' => 'post')),         //根据故事ID获取故事内容(讲)  //NO
        'api/Tellingstory/showUploadWorks' => array('Api/Tellingstory/showUploadWorks', array('method' => 'post')),         //上传作品显示页(讲)
        'api/Tellingstory/getUserAlbum' => array('Api/Tellingstory/getUserAlbum', array('method' => 'post')),         //上传作品--选择专辑
        'api/Tellingstory/createUserAlbum' => array('Api/Tellingstory/createUserAlbum', array('method' => 'post')),         //用户新建专辑(讲)
        'api/Tellingstory/getCreateScategory' => array('Api/Tellingstory/getCreateScategory', array('method' => 'get')),         //获取新建专辑分类

        'api/Tellingstory/saveDrafts' => array('Api/Tellingstory/saveDrafts', array('method' => 'post')),         //加入草稿箱


        'api/Listenstory/getSearchListenStory' => array('Api/Listenstory/getSearchListenStory', array('method' => 'post')),         //搜素专辑名(听)
        'api/Listenstory/getListenStoryCharge' => array('Api/Listenstory/getListenStoryCharge', array('method' => 'get')),         //获取听故事收费榜
        'api/Listenstory/getPageSearch' => array('Api/Listenstory/getPageSearch', array('method' => 'get')),         //首页筛选


        'api/User/getOpenID' => array('Api/User/getOpenID', array('method' => 'post')),         //获取openID
        'api/User/wxLogin' => array('Api/User/wxLogin', array('method' => 'post')),         //用户登陆 返回Token
        'api/User/wxPhone' => array('Api/User/wxPhone', array('method' => 'post')),         //获取用户手机号
        'api/User/PersonalCenter' => array('Api/User/PersonalCenter', array('method' => 'post')),         //个人中心

        'api/collectFormID' => array('Api/Index/collectFormID', array('method' => 'post')),         //收集formID
        'api/checkSignature' => array('Api/Index/checkSignature', array('method' => 'post')),         //发送模板消息







    ),
//    'TMPL_EXCEPTION_FILE' => APP_PATH.'/Public/exception.tpl',
    'DEFAULT_AJAX_RETURN' => 'JSON', // 默认AJAX 数据返回格式,可选JSON XML ...

);
?>








