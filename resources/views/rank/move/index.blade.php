<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>移动端排名</title>
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
<div class="x-nav">
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <xblock>
        <button class="layui-btn" onclick="x_admin_show('添加移动优化任务','{{url('move/create')}}')"><i class="layui-icon"></i>添加移动排名任务</button>
        <span class="x-right" style="line-height:40px">共有数据：{{$count}} 条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>订单编号</th>
            <th>搜索引擎</th>
            <th>关键词</th>
            <th>网址</th>
            <th>初始排名</th>
            <th>当前排名</th>
            <th>提交日期</th>
            <th>优化状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>

        @foreach($keyword as $key=>$item)
            <tr>
                <td>
                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$item->id}}'><i class="layui-icon">&#xe605;</i></div>
                </td>
                <td id="keyword{{$item->id}}">{{$item->id}}</td>
                <td>{{\App\SearchEngines::getsearchengines($item->searchengines)->name}}</td>
                <td>{{$item->keyword}}</td>
                <td>{{$item->dohost}}</td>
                <td>{{$item->rank}}</td>
                <td>@if($item->newrank ==0) 暂未上升 @else {{$item->newrank}} @endif</td>
                <td>{{$item->created_at}}</td>
                <td id="status{{$item->id}}">@if($item->status==0)未优化 @elseif($item->status==1)<span style="color: red">优化中</span> @elseif($item->status == 2) 暂停优化  @endif </td>
                <td class="td-manage">
                    <a title="确认优化"  onclick="optimize(this,{{$item->id}})" href="javascript:;">
                        <i class="layui-icon">&#xe63c;</i>
                    </a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
    <div class="page">
        {{ $keyword->links() }}

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

    function optimize(obj,id){
        layer.confirm('确定开始优化?',function(index){
            //发异步删除数据
            // $(obj).parents("tr").remove();
            $.ajax({
                type:"post",//type可以为post也可以为get
                url:"/api/keyword/updatestatus",
                data:{"id": id, "status": 1,"type":2,"userid":{{\Illuminate\Support\Facades\Auth::user()->id}}},//这行不能省略，如果没有数据向后台提交也要写成data:{}的形式
                dataType:"json",//这里要注意如果后台返回的数据不是json格式，那么就会进入到error:function(data){}中
                async:false,
                success:function(data){
                    //发异步，把数据提交给php
                    console.log(data.msg);
                    if (data.status==200) {
                        $("#status" + id).html("优化中");
                        layer.msg(data.msg,{icon:1,time:1000});
                    }else{
                        layer.msg(data.msg,{icon:1,time:1000});
                    }
                },
                error:function(data){

                }
            });
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
