<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>添加单个任务</title>
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
                <div class="layui-form-item">
                    <label class="layui-form-label">收索引擎</label>
                    <div class="layui-input-block">
                        @foreach(\App\SearchEngines::all() as $key=>$item)
                            @if($key ==0)
                                <input type="checkbox" value="{{$item->id}}" checked name="searchengineid[{{$key}}]" title="{{$item->name}}">
                            @else
                                <input type="checkbox" value="{{$item->id}}" name="searchengineid[{{$key}}]" title="{{$item->name}}">
                            @endif
                        @endforeach
                    </div>
                </div>

            <div class="layui-form-item layui-form-text" style="margin-top: 12px">
                <label for="desc" class="layui-form-label">
                    <b>关键词</b>
                </label>
                {{csrf_field()}}
                <div class="layui-input-block">
                    <input type="text" id="keyword" name="keywords" value=""  style="width: 250px" required="" lay-verify="keyword"
                           autocomplete="off" class="layui-input">
                </div>

            </div>
                <div class="layui-form-item layui-form-text" style="margin-top: 12px">
                    <label for="desc" class="layui-form-label">
                        <b style="color: red">匹配网址</b>
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="dohost" name="dohost" value="" style="width: 250px" required="" lay-verify="lianjie"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-form-text" style="margin-top: 12px">
                    <label for="desc" class="layui-form-label">
                        <b >日点击</b>
                    </label>
                    <div class="layui-input-block">
                        <input type="number" id="click" name="click" value="" style="width: 250px" required="" lay-verify="count"
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
                            url:"/task/create",
                            data:data.field,//这行不能省略，如果没有数据向后台提交也要写成data:{}的形式
                            dataType:"json",//这里要注意如果后台返回的数据不是json格式，那么就会进入到error:function(data){}中
                            async:true,
                            success:function(data){
                                //发异步，把数据提交给php
                                if (data.status==200){
                                    layer.confirm(data.msg, {

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

            <div class="layui-col-md7">
                    <fieldset class="layui-elem-field">
                        <legend style="color: red">收费标准</legend>
                        <div class="layui-field-box">
                            <table class="layui-table">
                                <tbody>
                                <tr>
                                    <th>1</th>
                                    <td>收费标准：每点击1次消耗1积分，每次默认点击内页pv为4-6次，总停留时间为45-60秒。</td>
                                </tr>
                                <tr>
                                    <th>2</th>
                                    <td>操作说明：点击添加任务后，会立即执行，产生积分消耗，请确认关键词、网址无误后再添加。</td>
                                </tr>
                                <tr>
                                    <th>3</th>
                                    <th>网址不用带http，点击量最低设置为2次</th>
                                <tr>

                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                    <fieldset class="layui-elem-field">
                        <legend style="color: red">注意</legend>
                        <div class="layui-field-box">
                            <table class="layui-table">
                                <tbody>
                                <tr>
                                    <th>1</th>
                                    <td>排名优化周期为7-15天，如果优化时间过短，达不到优化效果</td>
                                </tr>
                                <tr>
                                    <th>2</th>
                                    <th>说明：按以上规定页数未能搜索到您的网站一样扣积分。</th>
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
