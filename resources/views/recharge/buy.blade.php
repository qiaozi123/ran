<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>主页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

    <link rel="stylesheet" href="{{url('css/font.css')}}">
    <link rel="stylesheet" href="{{url('css/xadmin.css')}}">
</head>
<body>
<div class="x-body layui-anim layui-anim-up">

    <fieldset class="layui-elem-field">
        <legend>充值信息</legend>
        <div class="layui-field-box">
            <table class="layui-table">
                <tbody>
                <tr>
                    <th>产品编号</th>
                    <td>描述</td>
                    <td>排名币</td>
                    <td>原价</td>
                    <td>优惠价</td>
                    <td>购买</td>
                </tr>
                @foreach($price as $item)
                <tr>
                    <th>{{$item->id}}</th>
                    <td>{{$item->desc}}</td>
                    <td>{{$item->rankcoin}}</td>
                    <td>{{$item->price}}</td>
                    <td>{{$item->preferential}}</td>
                    <td><a href="/recharge"><input type="button" value="充值"></a></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>

</div>

</body>
</html>
