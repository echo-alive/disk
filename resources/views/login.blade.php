<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        Login-WeDisk</title>
    <link rel="stylesheet"
          href="/styles/css/style.css">
    <link rel="stylesheet"
          href="/styles/css/swiper.min.css">
    <script src="/layui/layui.js"
            charset="utf-8"></script>

    <script src="/styles/js/swiper.min.js"></script>
    <style>

        .swiper-container {
            width: 100%;
            height: 100%;
            position: fixed;
            left: 0;
            top: 0;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;

            /* Center slide text vertically */
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }

        .swiper-slide .pic {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .swiper-slide .pic img {
            min-width: 100%;
            height: 100%;
            display: block;
            position: absolute;
            left: 0;
            top: 0;
        }

        .login-box {
            width: calc(100% - 60px);
            height: 90%;
            position: fixed;
            right: 60px;
            top: 0;
            background-color: transparent;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box .form {
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            padding: 20px 30px;
            min-width: 352px;
            background-color: #fff;
        }

        .login-box .form .header {
            line-height: 40px;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        .login-box .form .tips {
            color: red;
            height: 18px;
            margin-bottom: 20px;
            text-align: center;
        }

        .login-box .form .form-item {
            margin-bottom: 20px;
        }

        .login-box .form .form-item input {
            background-color: #fff;
            outline: none;
            -webkit-box-shadow: 0 0 0px 1000px white inset;
            padding: 0 10px;
            width: calc(100% - 20px);
            height: 40px;
            border: 1px solid #ebebeb;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }

        .login-box .form .form-item button {
            width: 100%;
            height: 40px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            background-color: #09aaff;
            color: #fff;
            margin-top: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body  id="mydiv">
<!-- Swiper -->
<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <div class="pic">
                <img src="/styles/images/bg1.jpg"
                     alt="">
            </div>
        </div>
        <div class="swiper-slide">
            <div class="pic">
                <img src="/styles/images/bg2.jpg"
                     alt="">
            </div>
        </div>
        <div class="swiper-slide">
            <div class="pic">
                <img src="/styles/images/bg3.jpg"
                     alt="">
            </div>
        </div>
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
</div>
<div class="login-box">
    <div class="form">
        <form class="layui-form"
              action="">

            <p class="header">
                WeDisk
            </p>
            <div class="tips">

            </div>
            <input type="hidden"
                   name="_token"
                   class="token"
                   value="{{csrf_token()}}">
            <div class="form-item">
                <input type="text"
                       name="account"
                       placeholder="手机号/邮箱/用户名"
                       lay-verify="required"/>
            </div>
            <div class="form-item">
                <input type="password"
                       name="password"
                       placeholder="密码"
                       lay-verify="required"/>
            </div>
            <div class="form-item">
                <button class="layui-btn btn"
                        lay-submit="login"
                        lay-filter="login"
                        id="submit">
                    登录
                </button>

            </div>
        </form>
    </div>
</div>

<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        effect: 'fade',
        <!-- mousewheelControl : true, -->
        speed: 1000,
        observer: true,
        loop: true,
        observeParents: true,
        autoplayDisableOnInteraction: false,
        autoplay: 3000,
    });
</script>
</body>
<script type="text/javascript">
    layui.use(['form'], function () {
        var $ = layui.jquery;
        var form = layui.form
            ,
            layer = layui.layer;
        //加载层-默认风格
        /* layer.load(0, {shade: [1, '#fff']});
        if (localStorage.getItem('remLogin')!=null) {
            $.ajax({
                type: "post",//put delete get post
                url: "/login/checkLogin",
                data: JSON.parse(localStorage.getItem('remLogin')),
                success: function (res) {
                    window.location.href = res.link;
                }
            });
        } else {
            //此处演示关闭
            layer.closeAll('loading');
        }
 */
        //监听提交
        form.on('submit(login)', function (data) {
            layer.load(0, {shade: [0.1, '#fff']});
            $.ajax({
                type: "post",//put delete get post
                url: "/loginForm",
                data: data.field,
                success: function (res) {
                    if (res.msg) {
                        /* if (data.field.remember) {
                            var loginData = {
                                'staff_enname': data.field.staff_enname,
                                'staff_password': data.field.staff_password
                            }

                           localStorage.setItem('remLogin', JSON.stringify(loginData));
                        }
 */
                        setTimeout(function () {
                            window.location.href = res.link;
                        }, 1500);
                    } else {
                        layer.closeAll('loading');
                        layer.msg('账号或密码不正确！', function () {

                            //关闭后的操作
                            window.location.href = res.link;
                        });

                    }
                },
            });

            return false;
        });

        var config = {
            vx: 4,	//小球x轴速度,正为右，负为左
            vy: 4,	//小球y轴速度
            height: 2,	//小球高宽，其实为正方形，所以不宜太大
            width: 2,
            count: 200,		//点个数
            color: "121, 162, 185", 	//点颜色
            stroke: "211,45,45", 		//线条颜色
            dist: 6000, 	//点吸附距离
            e_dist: 20000, 	//鼠标吸附加速距离
            max_conn: 10, 	//点到点最大连接数
        }

        //调用
        CanvasParticle(config);

    })
</script>
<script src="/styles/js/canvas-particle.js"></script>

</html>