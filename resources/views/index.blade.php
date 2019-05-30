<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@if(empty($proxy))白帽子点击系统@else {{$proxy->title}}点击系统 @endif</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="{{url('css/font.css')}}">
    <link rel="stylesheet" href="{{url('css/xadmin.css')}}">
    <script type="text/javascript" src="{{url('js/jquery.min.js')}}"></script>
    <script src="{{url('lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{url('js/xadmin.js')}}"></script>
</head>
<body>
<!-- 顶部开始 -->
<div class="container">
    <div class="logo"><a href="/home">@if(empty($proxy))排名管理@else {{$proxy->title}}排名管理 @endif</a></div>
    <div class="left_open">
        <i title="展开左侧栏" class="iconfont">&#xe699;</i>
    </div>

    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">{{\Illuminate\Support\Facades\Auth::user()->name}}</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a href="/logout">退出</a></dd>
            </dl>
        </li>
    </ul>
</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6ce;</i>
                    <cite>任务管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="/task/list">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>任务列表</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="/task/one">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>单个添加任务</cite>
                        </a>
                    </li>
                    {{--<li>--}}
                        {{--<a _href="/move">--}}
                            {{--<i class="iconfont">&#xe6a7;</i>--}}
                            {{--<cite>批量添加任务</cite>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a _href="/move">--}}
                            {{--<i class="iconfont">&#xe6a7;</i>--}}
                            {{--<cite>第三方平台任务</cite>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe723;</i>
                    <cite>充值管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="/recharge">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>账户充值</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/buy">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>购买积分</cite>
                        </a>
                    </li >
                </ul>
            </li>

            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe726;</i>
                    <cite>账户管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="/user/info">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>基本信息</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="/user/update">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>修改密码</cite>
                        </a>
                    </li>
                </ul>
            </li>
            @if(\Illuminate\Support\Facades\Auth::check() && \App\User::role(\Illuminate\Support\Facades\Auth::user()->id,'admin'))
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe726;</i>
                    <cite>权限管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="/user">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>用户列表</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/role">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>角色列表</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/permission">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>权限列表</cite>
                        </a>
                    </li >
                </ul>
            </li>
            @endif

            @if(\Illuminate\Support\Facades\Auth::check() && \App\User::role(\Illuminate\Support\Facades\Auth::user()->id,'proxy'))
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe6ba;</i>
                        <cite>我的用户</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="/proxy/user/{{\Illuminate\Support\Facades\Auth::user()->id}}">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>用户列表</cite>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if(\Illuminate\Support\Facades\Auth::check() && \App\User::role(\Illuminate\Support\Facades\Auth::user()->id,'proxy'))
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe696;</i>
                        <cite>代理系统管理</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="/proxy/system/{{\Illuminate\Support\Facades\Auth::user()->id}}">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>平台设置</cite>
                            </a>
                        </li>
                        <li>
                            <a _href="/proxy/recharge/{{\Illuminate\Support\Facades\Auth::user()->id}}">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>扣款设置</cite>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif


        </ul>
    </div>
</div>
<!-- <div class="x-slide_left"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li>我的主页</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src='/list' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    layui.use('layer', function(){ //独立版的layer无需执行这一句
        var $ = layui.jquery, layer = layui.layer; //独立版的layer无需执行这一句

        //触发事件
        var active = {
            setTop: function(){
                var that = this;
                //多窗口模式，层叠置顶
                layer.open({
                    type: 2 //此处以iframe举例
                    ,title: '当你选择该窗体时，即会在最顶端'
                    ,area: ['390px', '260px']
                    ,shade: 0
                    ,maxmin: true
                    ,offset: [ //为了演示，随机坐标
                        Math.random()*($(window).height()-300)
                        ,Math.random()*($(window).width()-390)
                    ]
                    ,content: '//layer.layui.com/test/settop.html'
                    ,btn: ['继续弹出', '全部关闭'] //只是为了演示
                    ,yes: function(){
                        $(that).click();
                    }
                    ,btn2: function(){
                        layer.closeAll();
                    }

                    ,zIndex: layer.zIndex //重点1
                    ,success: function(layero){
                        layer.setTop(layero); //重点2
                    }
                });
            }
            ,confirmTrans: function(){
                //配置一个透明的询问框
                layer.msg('大部分参数都是可以公用的<br>合理搭配，展示不一样的风格', {
                    time: 20000, //20s后自动关闭
                    btn: ['明白了', '知道了', '哦']
                });
            }
            ,notice: function(){
                //示范一个公告层
                layer.open({
                    type: 1
                    ,title: false //不显示标题栏
                    ,closeBtn: false
                    ,area: '300px;'
                    ,shade: 0.8
                    ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    ,btn: ['火速围观', '残忍拒绝']
                    ,btnAlign: 'c'
                    ,moveType: 1 //拖拽模式，0或者1
                    ,content: '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">你知道吗？亲！<br>layer ≠ layui<br><br>layer只是作为Layui的一个弹层模块，由于其用户基数较大，所以常常会有人以为layui是layerui<br><br>layer虽然已被 Layui 收编为内置的弹层模块，但仍然会作为一个独立组件全力维护、升级。<br><br>我们此后的征途是星辰大海 ^_^</div>'
                    ,success: function(layero){
                        var btn = layero.find('.layui-layer-btn');
                        btn.find('.layui-layer-btn0').attr({
                            href: 'http://www.layui.com/'
                            ,target: '_blank'
                        });
                    }
                });
            }
            ,offset: function(othis){
                var type = othis.data('type')
                    ,text = othis.text();

                layer.open({
                    type: 1
                    ,offset: type //具体配置参考：http://www.layui.com/doc/modules/layer.html#offset
                    ,id: 'layerDemo'+type //防止重复弹出
                    ,content: '<div style="padding: 20px 100px;">'+ text +'</div>'
                    ,btn: '关闭全部'
                    ,btnAlign: 'c' //按钮居中
                    ,shade: 0 //不显示遮罩
                    ,yes: function(){
                        layer.closeAll();
                    }
                });
            }
        };

        layer.open({
            type: 1
            ,title: false //不显示标题栏
            ,closeBtn: false
            ,area: '800px;'
            ,shade: 0.8
            ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
            ,btn: [ '我知道了']
            ,btnAlign: 'c'
            ,moveType: 1 //拖拽模式，0或者1
            ,content: '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">你知道吗？亲！' +
            '<br>1. 成都白帽子教程上线 （2019.3.12）\n' +
            '对于新注册的会员可以先看 白帽子快排提升排名原理 \n' +
            '\n' +
            '再和老会员一样观看 安全稳定的优化方案\n' +
            '\n' +
            '当然，如果你是新会员对后台不了解，还可以看看加词的视频\n' +
            '\n' +
            '如果在观看视频时有疑问，可以联系我们的客服反馈，我们在对视频做更进一步的优化!<br>' +
            '<br>2.再一次说明，在后台不能添加博彩类词、黄色类词等违法词汇，一旦发现，立即封号。积分不予退款。<br>'

            ,success: function(layero){

            }
        });

    });
</script>

<div class="page-content-bg"></div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<div class="footer">
    <div class="copyright">白帽子点击管理系统</div>
</div>
<!-- 底部结束 -->
</body>
</html>
