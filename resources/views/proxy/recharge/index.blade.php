<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>代理充值设置</title>
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
                <span class="x-red">*</span>每天单个词价格设置
            </label>
            <div class="layui-input-inline">
                <input type="number" id="L_pass" name="recharge" value="{{$proxy->recharge}}" required=""  lay-verify="required"
                       autocomplete="off" class="layui-input">
                <input type="password" id="L_pass" style="display: none" name="userid" value="{{\Illuminate\Support\Facades\Auth::user()->id}}" required=""
                       autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
               积分/一词/一天
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

        //自定义验证规则
        form.verify({
            nikename: function(value){
                if(value.length < 5){
                    return '昵称至少得5个字符啊';
                }
            }
            ,pass: [/(.+){6,12}$/, '密码必须6到12位']
            ,repass: function(value){
                if($('#L_pass').val()!=$('#L_repass').val()){
                    return '两次密码不一致';
                }
            }
        });

        //监听提交
        form.on('submit(add)', function(data){
            console.log(data.field);
            //发异步，把数据提交给php
            $.ajax({
                type:"post",//type可以为post也可以为get
                url:"/proxy/recharge",
                data:data.field,//这行不能省略，如果没有数据向后台提交也要写成data:{}的形式
                dataType:"json",//这里要注意如果后台返回的数据不是json格式，那么就会进入到error:function(data){}中
                async:false,
                success:function(data){
                    //发异步，把数据提交给php
                    if (data.status==200){
                        layer.alert(data.status, {icon: 6},function () {
                            // 获得frame索引

                        });

                    }else{
                        layer.alert(data)
                    }
                },
                error:function(data){

                }
            });

        });


    });
</script>

</body>

</html>
