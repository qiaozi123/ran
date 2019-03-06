<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>welcome</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="{{url('css/font.css')}}">
    <link rel="stylesheet" href="{{url('css/xadmin.css')}}">
    <script type="text/javascript" src="{{url('js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{url('lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{url('js/xadmin.js')}}"></script>
</head>
<body>
<div class="x-body layui-anim layui-anim-up">
    <fieldset class="layui-elem-field">
        <legend>基本信息</legend>
        <div class="layui-field-box">
            <table class="layui-table">
                <tbody>
                <tr>
                    <th>用户名</th>
                    <td>{{$user->name}}</td>
                </tr>
                <tr>
                    <th>推送币</th>
                    <td style="color: red">{{$user->coin}}</td>
                </tr>
                <tr>
                    <th>手机号码</th>
                    <td>{{$user->telphone}}</td></tr>
                <tr>
                    <th>邮箱</th>
                    <td>{{$user->email}}</td></tr>
                <tr>
                    <th>qq</th>
                    <td>{{$user->qq}}</td></tr>
                </tbody>
            </table>
        </div>
    </fieldset>

</div>

</body>
</html>
