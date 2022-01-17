document.getElementById('showHidePassword').onclick = function () {
    var x = document.getElementById('password');
    var xr = document.getElementById('password_password_repeat');
    if (x.type === 'password'){
        x.type = 'text';
        xr.type = 'text';
    }
    else {
        x.type = 'password';
        xr.type = 'password';
    }

}