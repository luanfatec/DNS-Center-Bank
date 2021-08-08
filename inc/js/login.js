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
})