<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="{{url('css/font.css')}}">
    <link rel="stylesheet" href="{{url('css/xadmin.css')}}">
    <script type="text/javascript" src="{{url('js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{url('lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{url('js/xadmin.js')}}"></script>
    <![endif]-->
</head>

<body>
<div class="x-body">
    <form class="layui-form" >
        {{csrf_field()}}
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>角色名
            </label>
            <div class="layui-input-inline">
                <input type="text"  name="name" required="" lay-verify="required"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>slug
            </label>
            <div class="layui-input-inline">
                <input type="text"  name="slug" required="" lay-verify="required"
                       autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>描述
            </label>
            <div class="layui-input-inline">
                <input type="text"  name="description" required="" lay-verify="required"
                       autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>级别
            </label>
            <div class="layui-input-inline">
                <input type="text" name="level" required="" lay-verify="required"
                       autocomplete="off" class="layui-input">
            </div>
        </div>

</tbody>
</table>


<div class="layui-form-item">
    <label for="L_repass" class="layui-form-label">
    </label>
    <button class="layui-btn" lay-filter="add" lay-submit="">
        确认添加角色
    </button>
</div>
</form>
</div>
<script>
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        form.on('submit(add)', function(data){
            console.log(data.field);
            $.ajax({
                type:"post",//type可以为post也可以为get
                url:"/role/create",
                data:data.field,//这行不能省略，如果没有数据向后台提交也要写成data:{}的形式
                dataType:"json",//这里要注意如果后台返回的数据不是json格式，那么就会进入到error:function(data){}中
                async:false,
                success:function(data){
                    //发异步，把数据提交给php
                    // if (data==200){
                        layer.alert("增加成功", {icon: 6},function () {
                            // 获得frame索引
                            window.parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            //关闭当前frame
                            parent.layer.close(index);
                        });
                },
                error:function(data){

                }
            });

            return false;

        });
        // //监听提交
        // form.on('submit(add)', function(data){
        //     $.ajax({
        //         type:"post",//type可以为post也可以为get
        //         url:"/api/pc/docreate",
        //         data:date.field,//这行不能省略，如果没有数据向后台提交也要写成data:{}的形式
        //         dataType:"json",//这里要注意如果后台返回的数据不是json格式，那么就会进入到error:function(data){}中
        //         success:function(data){
        //             if (data==200){
        //                 layer.alert("增加成功", {icon: 6},function () {
        //                     // 获得frame索引
        //                     var index = parent.layer.getFrameIndex(window.name);
        //                     //关闭当前frame
        //                     parent.layer.close(index);
        //                 });
        //             }else{
        //                 layer.alert("增加失败,稍后再试", {icon: 6},function () {
        //                     // 获得frame索引
        //                     var index = parent.layer.getFrameIndex(window.name);
        //                     //关闭当前frame
        //                     parent.layer.close(index);
        //                     return false;
        //                 });
        //             }
        //         },
        //         error:function(data){
        //             layer.alert("服务器故障,稍后再试", {icon: 6},function () {
        //                 // 获得frame索引
        //                 var index = parent.layer.getFrameIndex(window.name);
        //                 //关闭当前frame
        //                 parent.layer.close(index);
        //             });
        //         }
        //     });
        //     //发异步，把数据提交给php
        // });

    });

    function getrank() {
        keyword = $(" #keyword ").val()
        dohost = $(" #dohost ").val()
        if (keyword == ''){
            alert('关键词不能为空');
            return
        }
        if (dohost == ''){
            alert('网址不能为空');
            return
        }
        var showload = layer.load(1, {
            shade: [0.1,'#fff'] //0.1透明度的白色背景
        });
        $('#rank').val('正在检测排名中...');

        $.ajax({
            type:"get",//type可以为post也可以为get
            url:"/search",
            data:{"keyword":keyword,'dohost':dohost},//这行不能省略，如果没有数据向后台提交也要写成data:{}的形式
            dataType:"json",//这里要注意如果后台返回的数据不是json格式，那么就会进入到error:function(data){}中
            success:function(data){
                layer.close(showload);
                $('#rank').val(data);
            },
            error:function(data){
                console.log(data.responseText)
                layer.close(showload);
                $('#rank').val(data.responseText);
            }
        });
    }
</script>

<script>

</script>
</body>

</html>
