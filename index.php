<?php
session_start();
if (isset($_SESSION["hash_pay"])) {
    header("location:painel.php");
}

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <title>DNS Center Bank</title>

    <style>

        body {
            background-image: url("images/empire-state-building-600001.jpg");
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        #section-header {
            height: 50vh;
            border: 2px solid rgba(232, 173, 173, 0.13);
        }

        #section-header .title-system h1 {
            color: rgba(255,255,255,.3);
            font-size: 10rem;
            text-align: center;
            margin-top: 2vh;
            display: block;
            z-index: 1;
        }

        #section-body-form {
            z-index: 10;
            width: 30vw;
            padding: 50px;
            background: #3f4850;
            height: 60vh;
            margin-top: -14%;
            border-radius: 10px;
            box-shadow: 3px 3px 11px rgba(0,0,0, .5);
            border: 2px solid rgba(242,5,5, .13);
        }

        #input-username::-webkit-outer-spin-button, #input-username::-webkit-inner-spin-button {
            display: none;
            -webkit-appearance: none;
            margin: 0;
        }

        #input-username[type=number] {
            -moz-appearance: textfield;
        }

        img#logo-login {
            width: 10vw;
        }

        .logo-login-box {
            text-align: center;
            margin-bottom: 9%;
        }

        form.row {
            margin: 10px;
        }

        button#btn-login, button#btn-register {
            background-color: gray;
            transform: none;
            transition: none;
            color: white;
            outline: none;
        }

        button#btn-login:focus,
        button#btn-register:focus {
            outline: none;
        }

        #create-account-lk, #login-account-lk {
            font-size: 1rem;
            font-weight: bold;
            color: #ffca00;
            cursor: pointer;
        }

        @media (min-width: 992px) and (max-width: 1199.98px) {
            #section-header .title-system h1 {
                font-size: 1rem;
            }
        }

    </style>
</head>
<body>

<main>
    <section class="container-fluid" id="section-header">
        <div class="title-system">
            <h1>DNS Center Bank</h1>
        </div>
    </section>

    <section class="container" id="section-body-form">
        <div>
            <div class="logo-login-box">
                <img src="images/logo1.png" class="img-fluid" id="logo-login">
            </div>

            <form class="row g-3 needs-validation" style="display: block" id="login-form">

                <div class="col-md-12">
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="validationTooltipUsernamePrepend">Conta</span>
                        <input type="email" class="form-control" id="input-username" required/>
                    </div>
                </div>

                <div class="col-md-12 mt-4">
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">Senha</span>
                        <input type="password" class="form-control" id="input-password" required/>
                    </div>
                </div>

                <div class="col-12 mt-4 row">
                    <div class="col-7">
                        <button id="btn-login" class="btn" type="submit">Acessar</button>
                    </div>
                    <div class="col-5">
                        <span id="create-account-lk" style="float: right">Criar uma conta</span>
                    </div>
                </div>

                <div class="col-12 mt-4 text-center">
                    <span id="span-notify" class="text-danger"></span>
                </div>

            </form>

            <form class="row g-3 needs-validation" style="display: none" id="form-register" name="form-register">

                <div class="col-md-12 mt-4">
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">Nome</span>
                        <input type="text" class="form-control" id="input-name-register" required/>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="validationTooltipUsernamePrepend">Conta</span>
                        <input type="email" class="form-control" id="input-username-register" required/>
                    </div>
                </div>

                <div class="col-md-12 mt-4">
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">Senha</span>
                        <input type="password" class="form-control" id="input-password-register" required/>
                    </div>
                </div>

                <div class="col-12 mt-4 row">
                    <div class="col-7">
                        <button id="btn-register" class="btn" type="submit">Cadastrar</button>
                    </div>
                    <div class="col-5">
                        <span id="login-account-lk" style="float: right">Fazer login</span>
                    </div>
                </div>

                <div class="col-12 mt-4 text-center">
                    <span id="span-notify-register" class="text-danger" style="color: #00ff00!important"></span>
                </div>

            </form>

        </div>
    </section>
</main>
<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="./inc/js/login.js"></script>
</body>
</html>