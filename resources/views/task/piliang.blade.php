<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>批量添加</title>
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
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so">
            <div class="layui-col-md5">
            <div class="layui-form-item layui-form-text" style="margin-top: 12px">
                <label for="desc" class="layui-form-label">
                    <b>关键词</b>
                </label>
        {{csrf_field()}}
                <div class="layui-input-block">
                    <textarea style="width: 400px;height: 150px" placeholder="一行一个关键词" id="desc" name="keywords" class="layui-textarea"></textarea>
                </div>

            </div>
            <div class="layui-form-item layui-form-text">
                <label for="desc" class="layui-form-label">
                    <b>搜索引擎</b>
                </label>
                <div class="layui-input-inline">
                    <select name="searchengineid">
                        @foreach(\App\SearchEngines::all() as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <button  class="layui-btn" lay-filter="add" lay-submit="">
                        确认修改
                    </button>
                </div>
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
                            url:"/task/piliang",
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
                                    layer.alert(data.msg, {icon: 2});
                                }
                            },
                            error:function(data){

                            }
                        });
                        return false

                    });

                });
            </script>

            <div class="layui-col-md7">
                    <fieldset class="layui-elem-field">
                        <legend style="color: red">注意</legend>
                        <div class="layui-field-box">
                            <table class="layui-table">
                                <tbody>
                                <tr>
                                    <th>1</th>
                                    <td>格式：关键词>网址>熊掌号>日最大点击量</td>
                                </tr>
                                <tr>
                                    <th>2</th>
                                    <td>每行一个任务,'>'符号前后不能有空格</td>
                                </tr>
                                <tr>
                                    <th>3</th>
                                    <th>每次最多添加300个关键词</th>
                                <tr>
                                <tr>
                                    <th>4</th>
                                    <th>网址不用带http，点击量最低设置为2次</th>
                                <tr>

                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                    <fieldset class="layui-elem-field">
                        <legend style="color: red">示例</legend>
                        <div class="layui-field-box">
                            <table class="layui-table">
                                <tbody>
                                <tr>
                                    <th>1</th>
                                    <td>淘宝网>www.taobao.com>熊掌号>30</td>
                                </tr>
                                <tr>
                                    <th>2</th>
                                    <td>淘宝商城>www.taobao.com>熊掌号>20</td>
                                </tr>
                                <tr>
                                    <th>3</th>
                                    <th>网上购物>www.taobao.com>熊掌号>10</th>
                                <tr>
                                <tr>
                                    <th>4</th>
                                    <th>淘宝购物>www.taobao.com>熊掌号>35</th>
                                <tr>

                                </tbody>
                            </table>
                        </div>
                    </fieldset>
            </div>


        </form>
    </div>



</div>
<script>

</script>

</body>

</html>
