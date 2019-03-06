<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>用户登录</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="{{url('css/font.css')}}">
    <link rel="stylesheet" href="{{url('css/xadmin.css')}}">
    <script type="text/javascript" src="{{url('js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{url('lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{url('js/xadmin.js')}}"></script>

</head>

<body class="login-bg">

<div class="login" style="   min-height: 620px;">
    <div class="message">注册账号</div>
    <div id="darkbannerwrap"></div>
    <div class="form-group">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="color:red;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <form method="get" action="/doregist" class="layui-form layui-col-md12 " >
        <input name="name" placeholder="用户名"  type="text" lay-verify="required" class="layui-input"  >
        <hr class="hr15">
        <input name="password" lay-verify="required" id="password" placeholder="密码"  type="password" class="layui-input">
        <hr class="hr15">
        <input name="repassword" lay-verify="regPwd" id="repassword" placeholder="重复密码"  type="password" class="layui-input">
        <hr class="hr15">
        <input name="telphone" placeholder="手机号"  type="number"  class="layui-input" lay-verify="phone">
        <hr class="hr15">
        <input name="qq" placeholder="qq号"  type="number" lay-verify="required" class="layui-input" >
        <hr class="hr15">
        <input name="email" placeholder="邮箱"  type="text" class="layui-input" lay-verify="email">
        <hr class="hr15">
        <input name="captcha" style="float:left;width: 59%"   placeholder="验证码"  type="text" lay-verify="required" class="code" >
        <img class="getcode" src="{{url('captcha')}}" onclick="this.src='{{ url('captcha/mews') }}?r='+Math.random();">
        <hr class="hr15">

        <input value="确认注册" lay-submit lay-filter="login" id="sub" style="width:100%;" type="submit">
        <hr class="hr20" >
    </form>

    <script>
        $("#sub").click(function(){
            password = $(" #password ").val()
            repassword = $(" #repassword ").val()
            if (password != repassword){
                return '两次输入的密码不一致';
            }
        });

        function checkPhone(){
            var phone = document.getElementById('phone').value;
            if(!(/^1[34578]\d{9}$/.test(phone))){
                alert("手机号码有误，请重填");
                return false;
            }
        }
    </script>
    <script>
        layui.use(['form', 'jquery'], function() {
            var $ = layui.jquery;
            var form = layui.form;
            form.verify({
                regPwd: function(value) {
                    //获取密码
                    var pwd = $("#password").val();
                    if(!new RegExp(pwd).test(value)) {
                        return '两次输入的密码不一致';
                    }
                }
            });
        });
    </script>  


    <div><a href="/">已有账号？点击立刻登陆</a></div>
</div>

<script>



</script>


</body>
</html>
