//修改时用户列表
function editOpenlist1(e) {
    $.post(APP + '/Geomancer/getEditGeomancerInfo', {geomancer_id: e.id}, function (res) {
        console.log(res);
        for (var i = 0; i < res.data.domainList.length; i++) {
            arr1.push(res.data.domainList[i].id);
        }
        checkList(arr1, $('.selectElement'));
    });

}

//加载领域标签列表
function openList1() {
    var keyWord1 = $("#keyWord").val();
    $.post(APP + '/Geomancer/get_domainlabel', {page_id: page1, keyWord: keyWord1}, function (res) {
        console.log(res);
        var html = '';
        html += `<tr>
                    <td colspan="4" class="s-search">
                        <input name="keyWord" type="text" class="form-control" id="keyWord" placeholder="请输入关键词进行搜索">
                        <button type="button" class="btn btn-primary" onclick="openList1();" ><i class="fa fa-search" aria-hidden="true"></i>搜索</button>
                    </td>
                 </tr>`;
        if (res.code == 200) {
            for (var i = 0; i < res.data.length; i++) {
                html +=
                    `<tr onclick="CheckSelected(this);">
                            <td><input type="checkbox" name="selectElement" class="selectElement" data-json="${JSON.stringify(res.data[i]).replace(/"/g, '&quot;')}" data-name="multi-select" value="${res.data[i].id}" /></td>
                            <td><img style="width: 50px;height: 50px;border-radius: 5px;" src="${res.data[i].domainlabel_img}"></td>
                            <td>${res.data[i].domainlabel_title}</td>
                        </tr>
			            `;
            }
            html += `<tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-default" onclick="prevPage('openList1');">上一页</button>
                                <button type="button" class="btn btn-default" onclick="nextPage('openList1');">下一页</button>
                            </td>
                         </tr>`;
            $('#checkList').html(html);

            showSearch($('#keyWord'), keyWord1);

            initCheck($('input[data-name=multi-select]'));

            //点击后的回调
            checkCallback($('input.selectElement'), arr1, $('#domainlabel_id'));

            //取消点击后的回调
            unCheckCallback($('input.selectElement'), arr1, $('#domainlabel_id'));

            checkList(arr1, $('.selectElement'));

            showHover($(".checkList tr"));

        } else {
            showError();
        }

    });
}


//修改时服务标签列表
function editOpenlist2(e) {
    $.post(APP + '/Geomancer/getEditGeomancerInfo', {geomancer_id: e.id}, function (res) {
        console.log(res);
        for (var i = 0; i < res.data.serviceList.length; i++) {
            arr2.push(res.data.serviceList[i].id);
        }
        checkList(arr2, $('.selectElement2'));
    });

}

//加载服务标签列表
function openList2() {
    var keyWord2 = $("#keyWord2").val();
    $.post(APP + '/Geomancer/get_servicelabel', {page_id: page2, keyWord: keyWord2}, function (res) {
        console.log(res);
        var html = '';
        html += `<tr>
                    <td colspan="4" class="s-search">
                        <input name="keyWord" type="text" class="form-control" id="keyWord2" placeholder="请输入关键词进行搜索">
                        <button type="button" class="btn btn-primary" onclick="openList2();" ><i class="fa fa-search" aria-hidden="true"></i>搜索</button>
                    </td>
                 </tr>`;
    // <input type="text" class="form-control s-service-price" name="servicelabel_price" value="">
        if (res.code == 200) {
            for (var i = 0; i < res.data.length; i++) {
                html +=
                    `<tr>
                            <td><input type="checkbox" name="selectElement2" class="selectElement2" data-json="${JSON.stringify(res.data[i]).replace(/"/g, '&quot;')}" data-name="multi-select2" value="${res.data[i].id}" /></td>
                            <td><img style="width: 50px;height: 50px;border-radius: 5px;" src="${res.data[i].servicelabel_img}"></td>
                            <td>${res.data[i].servicelabel_title}</td>
                            <td style="width: 20%"><input type="text" class="form-control s-service-price" name="servicelabel_price" placeholder="/次"></td>
                        </tr>
                    `;
            }
            html += `<tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-default" onclick="prevPage('openList2');">上一页</button>
                                <button type="button" class="btn btn-default" onclick="nextPage('openList2');">下一页</button>
                            </td>
                         </tr>`;


            $('#checkList2').html(html);

            showSearch($('#keyWord2'), keyWord2);


            initCheck($('input[data-name=multi-select2]'));


            //点击后的回调
            checkServiceCallback($('input.selectElement2'), arr2, $('#servicelabel_id'));

            //取消点击后的回调
            unCheckCallback($('input.selectElement2'), arr2, $('#servicelabel_id'));

            checkList(arr2, $('.selectElement2'));

            showHover($(".checkList tr"));

        } else {
            showError();
        }

    });

}



//修改时客服列表
function editOpenlist3(e) {
    $.post(APP + '/Geomancer/getEditGeomancerInfo', {geomancer_id: e.id}, function (res) {
        console.log(res);
        for (var i = 0; i < res.data.customerList.length; i++) {
            arr3.push(res.data.customerList[i].id);
        }
        checkList(arr3, $('.selectElement3'));
    });

}


//加载客服列表
function openList3() {
    var keyWord3 = $("#keyWord3").val();
    $.post(APP + '/Geomancer/get_customer', {page_id: page3, keyWord: keyWord3}, function (res) {
        console.log(res);
        var html = '';
        html += `<tr>
                    <td colspan="4" class="s-search">
                        <input name="keyWord" type="text" class="form-control" id="keyWord3" placeholder="请输入关键词进行搜索">
                        <button type="button" class="btn btn-primary" onclick="openList3();" ><i class="fa fa-search" aria-hidden="true"></i>搜索</button>
                    </td>
                 </tr>`;
        if (res.code == 200) {
            for (var i = 0; i < res.data.length; i++) {
                html +=
                    `<tr>
                            <td><input type="checkbox" name="selectElement3" class="selectElement3" data-json="${JSON.stringify(res.data[i]).replace(/"/g, '&quot;')}" data-name="multi-select3" value="${res.data[i].id}" /></td>
                            <!--<td><img style="width: 50px;height: 50px;border-radius: 5px;" src="${res.data[i].servicelabel_img}"></td>-->
                            <td>${res.data[i].customer_name}</td>
                            <td>${res.data[i].customer_tel}</td>
                        </tr>
                    `;
            }
            html += `<tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-default" onclick="prevPage('openList3');">上一页</button>
                                <button type="button" class="btn btn-default" onclick="nextPage('openList3');">下一页</button>
                            </td>
                         </tr>`;


            $('#checkList3').html(html);

            showSearch($('#keyWord3'), keyWord3);


            initCheck($('input[data-name=multi-select3]'));


            //点击后的回调
            checkCallback($('input.selectElement3'), arr3, $('#customer_id'));

            //取消点击后的回调
            unCheckCallback($('input.selectElement3'), arr3, $('#customer_id'));

            checkList(arr3, $('.selectElement3'));

            showHover($(".checkList tr"));

        } else {
            showError();
        }

    });

}


// -------------public-----------------

//check初始化
function initCheck(multiselect) {
    multiselect.iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });
}


//点击后的回调
function checkCallback(selectElement, arr, hiddenID) {
    selectElement.on('ifChecked', function (event) {

        var data = JSON.parse(event.target.dataset.json);
        // console.log(data);
        if (arr.indexOf(data.id) == -1) {
            arr.push(data.id);
        }
        console.log(arr);
        hiddenID.val(arr.join(','));
    });
}

//点击服务后的回调
function checkServiceCallback(selectElement, arr, hiddenID) {
    selectElement.on('ifChecked', function (event) {
        var s_service_price = $('.s-service-price').val();
        if(!s_service_price){
            alert(2222);
            console.log($(this))
            $(this).prop('disabled', "");
        }
        // var data = JSON.parse(event.target.dataset.json);
        // // console.log(data);
        // if (arr.indexOf(data.id) == -1) {
        //     arr.push(data.id);
        // }
        // console.log(arr);
        // hiddenID.val(arr.join(','));
    });
}

//取消点击后的回调
function unCheckCallback(selectElement, arr, hiddenID) {
    //取消点击后的回调
    selectElement.on('ifUnchecked', function (event) {
        var data = JSON.parse(event.target.dataset.json);
        // console.log(data);
        arr.splice($.inArray(data.id, arr), 1);
        console.log(arr);
        hiddenID.val(arr.join(','));

    });
}


//选中
function checkList(arr1, selectElement) {
    if (arr1) {
        var checkList = selectElement;
        $.each(arr1, function (index, value) {
            $.each(checkList, function (ind, val) {
                if (value == $(val).val()) {
                    $(checkList[ind]).iCheck('check');
                }
            });
        });
    }
}

//下一页
function nextPage(s_openList) {
    if (s_openList == 'openList1') {
        page1++;
        openList1();
    } else if (s_openList == 'openList2') {
        page2++;
        openList2();
    } else if (s_openList == 'openList3') {
        page3++;
        openList3();
    }

}

//上一页
function prevPage(s_openList) {
    if (s_openList == 'openList1') {
        if (page1 == 1) {
            page1 = 1;
        } else {
            page1--;
        }
        openList1();
    } else if (s_openList == 'openList2') {
        if (page2 == 1) {
            page2 = 1;
        } else {
            page2--;
        }
        openList2();
    } else if (s_openList == 'openList3') {
        if (page3 == 1) {
            page3 = 1;
        } else {
            page3--;
        }
        openList3();
    }
}


//hover
function showHover(element) {
    element.hover(function () {
        $(this).css("background-color", "#f7f7f7");
    }, function () {
        $(this).css("background-color", "#fff");
    });
}

//搜索回显
function showSearch(element, keyWord) {
    element.val(keyWord);

}


//错误提示
function showError() {
    $.show({
        title: '提示',
        isConfirm: false,
        content: '暂无内容'
    });
    return false;
}