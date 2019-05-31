<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>白帽子点击管理</title>
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

<div class="login">
    <div class="message">登录</div>
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
    <form method="get" action="/dologin" class="layui-form" >
        <input name="username" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
        <hr class="hr15">
        <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
        <hr class="hr20" >

        <input name="captcha" style="float:left;width: 59%"   placeholder="验证码"  type="text" lay-verify="required" class="code" >
        <img class="getcode" src="{{url('captcha')}}" onclick="this.src='{{ url('captcha/mews') }}?r='+Math.random();">
        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
        <hr class="hr20" >
    </form>
    <div><a href="/regist">没有账号？点击立刻注册</a></div>
</div>

<script>



</script>


</body>
</html>
