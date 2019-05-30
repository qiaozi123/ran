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
    <blockquote class="layui-elem-quote">欢迎用户:<span class="x-red">{{\Illuminate\Support\Facades\Auth::user()->name}}</span>！    权限 : <span class="x-red">{{\App\User::roledata(\Illuminate\Support\Facades\Auth::user()->id)->name}}</span></blockquote>
    <fieldset class="layui-elem-field">
        <legend>数据一览</legend>
        <div class="layui-field-box">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                            <div carousel-item="">
                                <ul class="layui-row layui-col-space10 layui-this">
                                    <li class="layui-col-xs2">
                                        <a href="javascript:;" class="x-admin-backlog-body">
                                            <h3>优化中的任务</h3>
                                            <p>
                                                <cite>{{$youhuazhong}}</cite></p>
                                        </a>
                                    </li>


                                    <li class="layui-col-xs2">
                                        <a href="javascript:;" class="x-admin-backlog-body">
                                            <h3>暂停的任务</h3>
                                            <p>
                                                <cite>{{$zhantingzhong}}</cite></p>
                                        </a>
                                    </li>

                                    <li class="layui-col-xs2">
                                        <a href="javascript:;" class="x-admin-backlog-body">
                                            <h3>积分</h3>
                                            <p>
                                                <cite>{{$user->coin}}</cite></p>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field">
        <legend>系统通知</legend>
        <div class="layui-field-box">
            <table class="layui-table" lay-skin="line">
                <tbody>
                @foreach($msg as $item)
                <tr>
                    <td >
                        <a class="x-a" >{{$item->msg}}</a>
                    </td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field">
        <legend>开发团队</legend>
        <div class="layui-field-box">
            <table class="layui-table">
                <tbody>
                <tr>
                    <th>版权所有</th>
                    <td>{{url('')}}
                        <a href="{{url('')}}" class='x-a' target="_blank">访问官网</a></td>
                </tr>
                </tbody>
            </table>
        </div>
    </fieldset>
    <blockquote class="layui-elem-quote layui-quote-nm">佛系 桔梗 大宝 KING （赞助商:执墨）</blockquote>
</div>

</body>
</html>
