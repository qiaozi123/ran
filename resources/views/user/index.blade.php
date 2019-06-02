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
    <![endif]-->
</head>

<body>

<div class="x-body">
    <xblock>
        <a href="/user"><button class="layui-btn ">所有用户</button></a>
        @foreach(\Bican\Roles\Models\Role::all() as $item)
            <a href="/user?roleid={{$item->id}}"><button class="layui-btn ">{{$item->name}}</button></a>
        @endforeach

            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
                <i class="layui-icon" style="line-height:30px">ဂ</i>
            </a>
    </xblock>
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so">
            <input type="text" id="username" name="username"  value="@if(!empty($username)){{$username}} @else  @endif" placeholder="请输入用户名" autocomplete="off" class="layui-input">
            <p class="layui-btn"  onclick="search_user()" ><i class="layui-icon"></i></p>
            <script>
                function search_user() {
                    window.location.href = "/user?name="+$('#username').val();
                }
            </script>
        </form>
    </div>
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
            <th>积分</th>
            <th>角色</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
            @foreach($user as $key=>$item)
            <tr>
            <td>
                <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$item->id}}'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td>{{$item->id}}</td>
            <td><a title="当前用户:{{$item->name}}"  style="color:red;" onclick="x_admin_show('当前用户:{{$item->name}}','/keyword/{{$item->id}}')" href="javascript:;">{{$item->name}}</a></td>
            <td>{{$item->telphone}}</td>
            <td>{{$item->qq}}</td>
            <td>{{$item->email}}</td>
            <td>{{$item->coin}}</td>
            <td>{{$item->rolename}}</td>
            <td class="td-manage">
                <a title="用户积分充值"  onclick="x_admin_show('用户:{{$item->name}}  积分充值','/user/recharge?userid={{$item->id}}')" href="javascript:;">
                    <i class="layui-icon">&#xe642;</i>
                </a>
                <a title="修改用户权限"  onclick="x_admin_show('修改用户:{{$item->name}}  权限','/role/user/update?userid={{$item->id}}')" href="javascript:;">
                    <i class="layui-icon">&#xe631;</i>
                </a>
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
    <div class="page">
        {{ $user->links() }}
    </div>

</div>
<script>
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

    /*用户-停用*/
    function member_stop(obj,id){
        layer.confirm('确认要停用吗？',function(index){

            if($(obj).attr('title')=='启用'){

                //发异步把用户状态进行更改
                $(obj).attr('title','停用')
                $(obj).find('i').html('&#xe62f;');

                $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                layer.msg('已停用!',{icon: 5,time:1000});

            }else{
                $(obj).attr('title','启用')
                $(obj).find('i').html('&#xe601;');

                $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                layer.msg('已启用!',{icon: 5,time:1000});
            }

        });
    }

    /*用户-删除*/
    function member_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            //发异步删除数据
            $(obj).parents("tr").remove();
            layer.msg('已删除!',{icon:1,time:1000});
        });
    }



    function delAll (argument) {

        var data = tableCheck.getData();

        layer.confirm('确认要删除吗？'+data,function(index){
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {icon: 1});
            $(".layui-form-checked").not('.header').parents('tr').remove();
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
