// start notification
function Notify(config) {
    $.confirm({
        title: "",
        content: config.message,
        type: config.color,
        typeAnimated: true,
        buttons: {
            tryAgain: {
                text: 'Ok',
                action: function(){
                }
            }
        }
    });
}
// End notification

$(window).ready(function() {

    $("#btn-login").click((e) => {
        window.event.preventDefault();
        $.post("login.php", {
            email: $("#input-username").val(), password: $("#input-password").val(), action: "login"
        }, function (response) {
            if (!response)
            {
                $("#span-notify").text("Usuário ou senha incorretos!");
            } else
            {
                window.location.href = "painel.php";
                window.location.reload();
            }
        });
    });

    $("#btn-register").click((e) => {
        e.preventDefault()
        $.post("login.php", {
            name: $("#input-name-register").val(),
            email: $("#input-username-register").val(),
            password: $("#input-password-register").val(), action: "register"
        }, function (response) {
            const resp = JSON.parse(response);

            if (resp.status)
            {
                Notify({ message: resp.message, color: "green"});
                window.location.reload();
            } else {
                Notify({ message: resp.message, color: "red"});
            }
        });
    });

    $("#create-account-lk").click(() => {
        document.getElementById("login-form").style.display = "none";
        document.getElementById("form-register").style.display = "block";
    });

    $("#login-account-lk").click(() => {
        document.getElementById("login-form").style.display = "block";
        document.getElementById("form-register").style.display = "none";
    });
})