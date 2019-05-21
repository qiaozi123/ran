<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>任务列表</title>
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
    任务列表(所有任务：0, 在线任务：0, 离线任务：0)
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">

    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="x_admin_show('批量添加','/task/piliang?userid={{\Illuminate\Support\Facades\Auth::user()->id}}')" href="javascript:;">批量添加</button>
        <button class="layui-btn layui-btn-danger"  onclick="piliang_run(this,0)" href="javascript:;" >批量运行</button>
        <button class="layui-btn layui-btn-danger"  onclick="piliang_stop(this,0)" href="javascript:;">批量停止</button>
        {{--<button class="layui-btn layui-btn-danger">批量设点</button>--}}
        <button class="layui-btn layui-btn-danger"><a href="/excel/export" style="color: white">导出当前</a></button>

    </xblock>
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so">
            <input type="text" name="keyword"  placeholder="关键词" autocomplete="off" class="layui-input">
            <input type="text" name="dohost"  placeholder="网址" autocomplete="off" class="layui-input">

            <div class="layui-input-inline">
                <select name="contrller">
                    @foreach(\App\SearchEngines::all() as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-input-inline">
                <select name="rank">
                    <option>排名情况</option>
                </select>
            </div>
            <div class="layui-input-inline">
                <select name="status">
                    <option>所有状态</option>
                </select>
            </div>
            <button class="layui-btn"  lay-submit="" onclick="search_keyword()" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            <script>

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
            <th>关键词</th>
            <th>网址</th>
            <th>搜索引擎</th>
            <th>初排</th>
            <th>新排</th>
            <th>变化</th>
            <th>排名时间</th>
            <th>日点</th>
            <th>已点</th>
            <th>添加时间</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $key=>$item)
        <tr>
            <td>
                <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$item->id}}'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td>{{$item->id}}</td>
            <td>{{$item->keyword}}</td>
            <td>{{$item->dohost}}</td>
            <td>{{$item->searchengines}}</td>
            <td>{{$item->rank}}</td>

            <td>{{$item->rank}}</td>
            <td>{{$item->rank}}</td>
            <td>{{$item->rank}}</td>

            <td>{{$item->click}}</td>
            <td>{{$item->click}}</td>
            <td>{{$item->created_at}}</td>
            <td class="td-status">@if($item->status==0)
                    <a onclick="member_stop(this,{{$item->id}})" href="javascript:;"  style="" title="未优化"><p style="color: red"><i class="layui-icon layui-icon-pause"></i>未优化</p></a>
                 @elseif($item->status ==1)
                    <a onclick="member_stop(this,{{$item->id}})" href="javascript:;"  style="" title="优化中"><p style="color: green"><i class="layui-icon layui-icon-ok-circle"></i>优化中</p></a>
                @elseif($item->status ==2)
                    <a onclick="member_stop(this,{{$item->id}})" href="javascript:;"  style="" title="未优化"><p style="color: red"><i class="layui-icon layui-icon-pause"></i>暂停中</p></a>
                @endif
            </td>
            <td >
                    <a onclick="x_admin_show('编辑','/task/update?id={{$item->id}}','500','500')" href="javascript:;" style="" title="编辑">
                        <span class="layui-btn layui-btn-normal layui-btn-mini"><font color="#f5f5dc">编辑</font></span>
                    </a>
                    <a onclick="member_del(this,{{$item->id}})" href="javascript:;"  title="删除">
                        <span class="layui-btn layui-btn-normal layui-btn-mini"><font color="#f5f5dc">删除</font></span>
                    </a>
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
    <div class="page">
        {{$data->links()}}
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

    // 任务批量启用
    function piliang_run(obj,id){
        var data = tableCheck.getData();
        if (data.length == 0){
            layer.msg('没有任务选中', {
                time: 2000, //2s后自动关闭
            });
            return
        }
        layer.confirm('是否启用选中任务.',function(index){
            //发异步删除数据
            $.ajax({
                type:"post",//type可以为post也可以为get
                url:"/task/updatestatus_many",
                data:{_token:"{{csrf_token()}}",id:data,status:1},//这行不能省略，如果没有数据向后台提交也要写成data:{}的形式
                dataType:"json",//这里要注意如果后台返回的数据不是json格式，那么就会进入到error:function(data){}中
                async:true,
                success:function(data){
                    //发异步，把数据提交给php
                    if (data.status==200){
                        layer.alert(data.msg, {icon: 6},function () {
                            window.location.reload();
                        });
                    }else{
                        lay.alert(data.msg)
                    }
                },
                error:function(data){

                }
            });
        });
    }

    // 任务批量停止
    function piliang_stop(obj,id){
        var data = tableCheck.getData();
        if (data.length == 0){
            layer.msg('没有任务选中', {
                time: 2000, //2s后自动关闭
            });
            return
        }
        layer.confirm('是否停止选中任务.',function(index){
            //发异步删除数据
            $.ajax({
                type:"post",//type可以为post也可以为get
                url:"/task/updatestatus_many",
                data:{_token:"{{csrf_token()}}",id:data,status:2},//这行不能省略，如果没有数据向后台提交也要写成data:{}的形式
                dataType:"json",//这里要注意如果后台返回的数据不是json格式，那么就会进入到error:function(data){}中
                async:true,
                success:function(data){
                    //发异步，把数据提交给php
                    if (data.status==200){
                        layer.alert(data.msg, {icon: 6},function () {
                            window.location.reload();
                        });
                    }else{
                        lay.alert(data.msg)
                    }
                },
                error:function(data){

                }
            });
        });
    }

    /*任务-停用-启用*/
    function member_stop(obj,id){

            if($(obj).attr('title')=='优化中'){
                //发异步把用户状态进行更改
                $.ajax({
                    type:"post",//type可以为post也可以为get
                    url:"/task/updatestatus",
                    data:{id:id,_token:"{{csrf_token()}}",status:3},//这行不能省略，如果没有数据向后台提交也要写成data:{}的形式
                    dataType:"json",//这里要注意如果后台返回的数据不是json格式，那么就会进入到error:function(data){}中
                    async:true,
                    success:function(data){
                        //发异步，把数据提交给php
                        if (data.status==200){
                            $(obj).attr('title','暂停中')
                            $(obj).find('i').html('&#xe62f;');
                            $(obj).parents("tr").find(".td-status").find('a').html('<p style="color: red"><i class="layui-icon layui-icon-pause"></i>暂停中</p>');
                            layer.msg('已暂停任务!',{icon: 5,time:1000});
                        }else{
                            lay.alert(data.msg)
                        }
                    },
                    error:function(data){

                    }
                });

            }else{
                $.ajax({
                    type:"post",//type可以为post也可以为get
                    url:"/task/updatestatus",
                    data:{id:id,_token:"{{csrf_token()}}",status:1},//这行不能省略，如果没有数据向后台提交也要写成data:{}的形式
                    dataType:"json",//这里要注意如果后台返回的数据不是json格式，那么就会进入到error:function(data){}中
                    async:true,
                    success:function(data){
                        //发异步，把数据提交给php
                        if (data.status==200){
                            $(obj).attr('title','优化中')
                            $(obj).find('i').html('&#xe601;');
                            $(obj).parents("tr").find(".td-status").find('a').html('<p style="color: green"><i class="layui-icon layui-icon-ok-circle"></i>优化中</p>');
                            layer.msg('已开启任务!',{icon: 5,time:1000});
                        }else{
                            lay.alert(data.msg)
                        }
                    },
                    error:function(data){

                    }
                });


            }

    }

    /*任务-删除*/
    function member_del(obj,id){
        layer.confirm('友情提示：正常任务优化7天以上，排名才会有变化.',{title:'是否删除此排名任务？'},function(index){
            //发异步删除数据
            $.ajax({
                type:"post",//type可以为post也可以为get
                url:"/task/delete",
                data:{id:id,_token:"{{csrf_token()}}"},//这行不能省略，如果没有数据向后台提交也要写成data:{}的形式
                dataType:"json",//这里要注意如果后台返回的数据不是json格式，那么就会进入到error:function(data){}中
                async:true,
                success:function(data){
                    //发异步，把数据提交给php
                    if (data.status==200){
                        layer.alert(data.msg, {icon: 6},function () {
                            // 获得frame索引
                            $(obj).parents("tr").remove();
                            layer.msg(data.msg,{icon:1,time:1000});
                        });
                    }else{
                        lay.alert(data.msg)
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

</body>

</html>
