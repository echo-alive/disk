<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>
        WeDisk</title>
    <link rel="stylesheet"
          href="/styles/css/style.css">

    <script src="/layui/layui.js"
            charset="utf-8"></script>
    <link rel="stylesheet"
          href="/layui/css/layui.css">
</head>
<body>
<div class="top-box">
    <h1>
        <a href="/goHome">
            <div>
                W
            </div>
            <div>
                e
            </div>
            <div>
                D
            </div>
            <div>
                i
            </div>
            <div>
                s
            </div>
            <div>
                k
            </div>
        </a>
    </h1>
    <div class="user-info">
        hi,
        <a href="">{{$data['username']}}</a>
        <a href="/logout">注销</a>
    </div>
</div>
<div class="left-box">
    <ul>
        <li>
            <a href="/goHome"
            >
                <img src="/styles/images/person.png"
                     alt="">我的</a>
        </li>
        <li>
            <a href="/goShare"
               class="on">
                <img src="/styles/images/share.png"
                     alt="">共用</a>
        </li>
    </ul>
</div>
<div class="right-box">
    <!--我的文件-->
    <div class="tool">
        <div class="upload-box">
            <div class="one-upload"
                 id="upload">
                <img src="/styles/images/upload.png"
                     alt="">

                上传
            </div>
            {{--<ul>--}}
            {{--<li class="file-upload">--}}
            {{--<input type="file"--}}
            {{--multiple>--}}
            {{--上传文件--}}
            {{--</li>--}}
            {{--<li class="pack-upload">--}}
            {{--<input type='file'--}}
            {{--webkitdirectory>--}}
            {{--上传文件夹--}}
            {{--</li>--}}
            {{--</ul>--}}
        </div>
        <div class="add-packbox">
            <img src="/styles/images/add-file.png"
                 alt="">
            新建文件夹
        </div>
        <div class="getCheckData">
            <img src="/styles/images/delete.png"
                 alt="">
            删除
        </div>
    </div>
    <table class="layui-hide"
           id="list"
           lay-filter="list"></table>
    <script type="text/html"
            id="barDemo">
        <a class="layui-btn layui-btn-xs"
           lay-event="show"
           style="background-color: transparent;margin-right: -10px;"><img
                    src="/styles/images/show.png"
                    alt=""></a>
        <a class="layui-btn layui-btn-xs"
           lay-event="download"
           style="background-color: transparent;"><img
                    src="/styles/images/download.png"
                    alt=""></a>
    </script>

</div>
</body>


<script>
    layui.use(['table', 'upload', 'laydate'], function () {
        var $ = layui.jquery;
        var form = layui.form;
        var table = layui.table;
        var upload = layui.upload;

        // refresh
        function refresh() {
            var index = layer.msg('查询中，请稍候...', {
                icon: 16,
                time: false,
                shade: 0
            });
            setTimeout(function () {
                table.reload('list', { //表格的id
                    url: '/shareDisk',
                });
                layer.close(index);
            }, 800);

        }

        //多图片上传
        upload.render({
            elem: '#upload'
            ,
            url: '/uploadFiles'
            ,
            data: {'_token': '{{csrf_token()}}'}
            ,
            accept: 'file'
            ,
            multiple: true

            ,
            done: function (res) {
                //如果上传失败
                refresh();
                if (res.status == 1) {
                    return layer.msg('上传成功');
                } else {//上传成功
                    layer.msg(res.message);
                }

            }
            ,
            error: function () {
                //演示失败状态，并实现重传
                return layer.msg('上传失败,请重新上传');
            }
        });


        // 点击获取数据
        function goFolder(data) {
            var index = layer.msg('查询中，请稍候...', {
                icon: 16,
                time: false,
                shade: 0
            });
            setTimeout(function () {
                table.reload('list', { //表格的id
                    url: '/shareDisk',
                    where: {
                        'folder_id': data
                    }
                });
                layer.close(index);
            }, 800);

        }

        //监听单元格编辑
        table.on('edit(list)', function (obj) {
            var value = obj.value //得到修改后的值
                ,
                data = obj.data //得到所在行所有键值
                ,
                field = obj.field; //得到字段
            $.ajax({
                type: "POST",
                url: "/editFolder",
                data: {
                    id: data.id,
                    file_name: value,
                    file_type: data.file_type,
                    _token: '{{csrf_token()}}'
                },
                success: function (data) {
                    refresh();
                }
            });
        });
        table.render({
            elem: '#list'
            ,
            url: '/shareDisk'
            ,
            title: '考勤数据表'
            ,
            cellMinWidth: 200 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            ,
            cols: [[
                {
                    type: 'checkbox',
                    fixed: 'left'
                }
                , {
                    field: 'file_name',
                    title: '文件名',
                    edit: 'text',
                    minWidth: 200,
                    unresize: true,
                    sort: true,
                    templet: function (res) {
                        //return '<a '+(res.file_type=="pack"?"data="+res.id:"href="+res.real_path)+'><img src="/styles/images/' + res.file_type + '.png" >' + res.file_name+'</a>';
                        return '<img class="icon-log" src="/styles/images/' + res.file_type + '.png"  style="display:none" onload="this.style.display=\'\'">' + res.file_name;
                    }
                }
                , {
                    field: 'file_size',
                    title: '大小',
                    width: 200,
                    unresize: true,
                    sort: true,
                    templet: function (res) {
                        return res.file_size ? (res.file_size / 1024 / 1024).toFixed(2) + 'M' : '--';
                    }
                }
                , {
                    field: 'update_time',
                    title: '修改日期',
                    width: 200
                }
                , {
                    fixed: 'right',
                    title: '操作',
                    width: 120,
                    toolbar: '#barDemo',
                }

            ]]
            ,
            id: 'list'
            ,
            page: true
            ,
            limits: [10, 20, 50, 100, 150, 200, 300]
            ,
            limit: 100 //每页默认显示的数量
        });

        $('.add-packbox').click(function () {
            //默认prompt
            layer.prompt({
                title: '文件夹名称'
            }, function (val, index) {
                $.ajax({
                    type: "POST",
                    url: "/addFolder",
                    data: {
                        folder_name: val,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (data) {
                        refresh();
                    }
                });
                layer.close(index);
            });

        })

        $('.getCheckData').click(function () {
            var checkStatus = table.checkStatus('list'),
                data = checkStatus.data;
            var newData = [];
            data.forEach(function (e, eidx) {
                newData.push({
                    'id': e.id,
                    'file_type': e.file_type
                });
            })
            layer.msg('你确定要删除么？', {
                time: 0 //不自动关闭
                ,
                btn: ['确定', '取消']
                ,
                yes: function (index) {
                    $.ajax({
                        type: "POST",
                        url: "/deleteAllFile",
                        data: {
                            newData: newData,
                            _token: '{{csrf_token()}}'
                        },
                        success: function (data) {
                            refresh();
                        }
                    });
                    layer.close(index);

                }
            });
        })

        //监听行工具事件
        table.on('tool(list)', function (obj) {
            var data = obj.data;
            //console.log(obj)
            if (obj.event === 'show') {
                if (data.file_type == 'pack') {
                    goFolder(data.id);
                    return;
                }
                else if (data.file_type == 'png' || data.file_type == 'jpg' || data.file_type == 'gif') {
                    layer.open({
                        type: 1,
                        offset: 'auto',
                        shade: 0.8,
                        area: ['100%', '100%'], //宽高
                        title: data.file_name,
                        content: '<img src="' + data.real_path + '" style="width:auto;display:block;margin:0 auto;height:100%;">'
                    });
                    return;
                }
                else (data.file_type == 'doc' || data.file_type == 'docx' || data.file_type == 'pdf' || data.file_type == 'xls' || data.file_type == 'xlsx' || data.file_type == 'ppt' || data.file_type == 'pptx' || data.file_type == 'txt')
                {
                    window.location.href = data.real_path;
                    return;
                }
            }
            if (obj.event === 'download') {
                if (data.file_type == 'pack') {
                    goFolder(data.id);
                    return;
                }
                else {
                    window.location.href = data.real_path;
                    return;
                }
            }
        });
        $('.layui-table-tool-self').remove();


    });


</script>
<script src="/styles/js/jquery.js"></script>
</html>