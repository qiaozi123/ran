<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>修改优化任务</title>
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
    <form class="layui-form">
        {{csrf_field()}}
        <div class="layui-form-item">
            <label for="L_pass" class="layui-form-label">
                <span class="x-red">*</span>搜索引擎
            </label>
            <div class="layui-input-inline">
                <select name="searchengines" disabled lay-filter="aihao">
                    {{$searchengines = \App\SearchEngines::find($data->searchengines) }}
                    <option value="{{$searchengines->id}}" selected>{{$searchengines->name}}</option>
                </select>

                <input type="password" id="userid" style="display: none" name="userid" value="{{\Illuminate\Support\Facades\Auth::user()->id}}" required=""
                       autocomplete="off" class="layui-input">
                <input type="password" id="keywordid" style="display: none" name="keyword_id" value="{{$data->id}}" required=""
                       autocomplete="off" class="layui-input">
            </div>

        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
                <span class="x-red">*</span>关键词
            </label>
            <div class="layui-input-inline">
                <input type="text" id="keyword" name="keyword" value="{{$data->keyword}}" required="" lay-verify="keyword"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
                <span class="x-red">*</span>网址
            </label>
            <div class="layui-input-inline">
                <input type="text" id="dohost" name="dohost" required="" value="{{$data->dohost}}" lay-verify="lianjie"
                       autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
                <span class="x-red">*</span>日点击
            </label>
            <div class="layui-input-inline">
                <input type="number" id="click" name="click" required="" value="{{$data->click}}"  lay-verify="count"
                       autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-filter="add" lay-submit="">
                确认修改
            </button>
        </div>
    </form>
</div>
<script>
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        form.verify({
            count: function(value){
                if(value.length ==""){
                    return '每日点击数不能为空';
                }
                if($('#click').val() < 0){
                    return '点击次数不能小于0';
                }
            },keyword:function (value) {
                if(value.length ==""){
                    return '关键词不能为空';
                }
            },lianjie:function (value) {
                if(value.length ==""){
                    return '链接不能为空';
                }
            }
        });

        //监听提交
        form.on('submit(add)', function(data){
            console.log(data.field);
            //发异步，把数据提交给php
            $.ajax({
                type:"post",//type可以为post也可以为get
                url:"/task/update",
                data:data.field,//这行不能省略，如果没有数据向后台提交也要写成data:{}的形式
                dataType:"json",//这里要注意如果后台返回的数据不是json格式，那么就会进入到error:function(data){}中
                async:true,
                success:function(data){
                    //发异步，把数据提交给php
                    if (data.status==200){
                        layer.alert(data.msg, {icon: 6},function () {
                            // 获得frame索引
                            window.parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            //关闭当前frame
                            parent.layer.close(index);
                        });
                    }else{
                        lay.alert(data)
                    }
                },
                error:function(data){

                }
            });
            return false

        });


    });
</script>

</body>

</html>
