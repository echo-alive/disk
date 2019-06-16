$('.login-box .form-item button').click(function () {
    var account = $('input[name=account]').val();
    var password = $('input[name=password]').val();
    if (!account) {
        $('.login-box .tips').text('手机号/邮箱/用户名不能为空！');
        return;
    } else {
        $('.login-box .tips').text('');
    }

    if (!password) {
        $('.login-box .tips').text('密码不能为空！');
        return;
    } else {
        $('.login-box .tips').text('');
    }
    $.ajax({
        type: "POST",
        url: "/loginForm",
        data: {
            account: account,
            password: password,
            _token: $('input.token').val()
        },
        success: function (data) {
           if(data.code==0){
               window.location.href='/';

           }else{
               $('.tips').text(data.msg);
           }
        }
    });

})