<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>用户列表</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="stylesheet" href="{{url('css/font.css')}}">
    <link rel="stylesheet" href="{{url('css/xadmin.css')}}">
    <script type="text/javascript" src="{{url('js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{url('lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{url('js/xadmin.js')}}"></script>
    <meta name="x-csrf-token" content="{{ csrf_token() }}">
    <![endif]-->
</head>

<body>

<div class="x-body">
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>

    <table class="layui-table">
        <thead>
        <tr>
            <th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>id</th>
            <th>用户名</th>
            <th>手机号</th>
            <th>QQ号</th>
            <th>邮箱</th>
            <th>PC积分</th>
            <th>移动积分</th>
            <th>角色</th>
            <th>备注</th>
        </tr>
        </thead>
        <tbody>
        @foreach($user as $key=>$item)
            <tr>
                <td>
                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$item->id}}'><i class="layui-icon">&#xe605;</i></div>
                </td>
                <td>{{$item->id}}</td>
                <td>{{$item->name}}</td>
                <td>{{$item->qq}}</td>
                <td>{{$item->telphone}}</td>
                <td>{{$item->email}}</td>
                <td>{{$item->coin}}</td>
                <td>{{$item->m_coin}}</td>
                <td>{{$item->rolename}}</td>
                <td><input value="{{$item->mark}}" id="mark{{$item->id}}" >  <input type="button" value="添加备注" onclick="setmark({{$item->id}})"></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="page">
        {{ $user->links() }}
    </div>

</div>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    layui.use('laydate', function(){
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
            elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#end' //指定元素
        });
    });

    function setmark(id){
        mark = $('#mark'+id).val();
        $.ajax({
            type:"post",//type可以为post也可以为get
            url:"/proxy/user/mark/update",
            data:{'userid':id,'mark':mark,'_token':'{{csrf_token() }}'},//这行不能省略，如果没有数据向后台提交也要写成data:{}的形式
            dataType:"json",//这里要注意如果后台返回的数据不是json格式，那么就会进入到error:function(data){}中
            async:false,
            success:function(data){
                //发异步，把数据提交给php
                // if (data==200){
                layer.alert("备注添加成功", {icon: 6},function () {
                    // 获得frame索引
                    window.location.reload();
                });
            },
            error:function(data){

            }
        });
    }


</script>
<script>var _hmt = _hmt || []; (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();</script>
</body>

</html>
