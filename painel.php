<?php
session_start();
if (!isset($_SESSION["hash_pay"])) {
    header("location:index.php");
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

    <title><?= $_SESSION["name"];?></title>

    <style>
        body {
            background-color: #bfd0dc;
        }

        #section-header {
            height: 40vh;
            border: 2px solid rgba(232, 173, 173, 0.13);
            background-color: #011526;

        }

        #section-header .title-system {
            margin-top: 10px;
            color: blanchedalmond;
            margin-left: 10px;
        }

        #section-header .exit-system {
            margin-top: 10px;
        }

        #section-header .exit-system a {
            color: white;
            text-decoration: none;
        }

        #section-body {
            background-color: white;
            margin-top: -12%;
            height: 70vh;
            border-radius: 10px;
            box-shadow: 0px 4px 11px rgba(0, 0, 0, .5);
            overflow-y: scroll;
            padding: 20px;
            position: relative;
        }

        #section-body::-webkit-scrollbar {
            display: none;
        }

        .profile-box {
            text-align: center;
            padding-top: 25px;
            padding-bottom: 18px;
            box-shadow: 0px 4px 11px rgba(0, 0, 0, .5);
            border-radius: 10px;
        }

        .greeting-user p {
            font-size: 1.3rem;
        }

        .img-box-profile {
            border-radius: 50%;
            width: 124px;
            height: 124px;
            border: 5px solid #e2e2e2;
        }

        .profile-body {
            margin-top: 10px;
        }

        .profile-body h4 {
            color: #022340;
        }

        .wallet-box {
            padding: 25px 55px;
            padding-bottom: 15px;
            box-shadow: 0px 4px 11px rgba(0, 0, 0, .5);
            border-radius: 10px;
        }

        .account-data {
            text-align: center;
            padding-top: 25px;
            padding-bottom: 18px;
            box-shadow: 0px 4px 11px rgba(0, 0, 0, .5);
            border-radius: 10px;
        }

        .box-logs-01 {
            padding: 25px 55px;
            padding-bottom: 18px;
            margin-top: 20px;
            box-shadow: 0px 4px 11px rgba(0, 0, 0, .5);
            border-radius: 10px;
        }

        .line-logs {
            text-align: center;
        }

        .line-logs span {
            position: relative;
            color: #022340;
            text-decoration: underline;
            font-weight: bold;
            margin-top: 2px;
        }

        .icon-status-log {
            position: absolute;
            display: inline-block;
            margin-left: -30px;
        }

        #btn-transfer, #btn-unblocked {
            background: #F29F05;
            border-radius: 10px;
            padding: 5px 28px;
            color: #011526;
            border: none;
            text-decoration: none;
        }

        .btn-edit-profile {
            background-color: #F29F05;
        }

        #log-content-transactions {
            text-align: center;
            overflow-y: scroll;
            height: 25vh;
        }

        #log-content-transactions::-webkit-scrollbar {
            display: none;
        }

        #log-content-transactions li {
            list-style: none;
        }

        #log-content-transactions li a {
            color: blue;
            font-size: .9rem;
            text-decoration: none;
            cursor: pointer;
        }

        #show-details {
            width: 15%;
            align-content: center;
            position: absolute;
            top: 30%;
        }

    </style>
</head>
<body>
<main>

    <section id="section-header" class="container-fluid">
        <div class="row">

            <div class="col-md-10">
                <div class="title-system">
                    <h1>DNS Center Bank</h1>
                </div>
            </div>

            <div class="col-md-2">
                <div class="exit-system">
                    <a style="cursor: pointer" id="exit-system-action">Sair</a>
                </div>
            </div>

        </div>
    </section>


    <section class="container" id="section-body">

        <div class="row">

            <div class="col-md-3">
                <div class="profile-box">

                    <div>
                        <div>
                            <img id="img-box-profile" class="img-box-profile border-gray" width="100" align="center">
                        </div>
                    </div>

                    <div class="profile-body">
                        <h4 id="name-proofile-image"></h4>
                        <p>
                            Seja bem vindo(a) <span>&#128512;</span>
                        </p>
                        <p>
                            <a href="edit-profile.php" class="btn btn-edit-profile">Editar perfil</a>
                        </p>
                    </div>

                </div>
            </div>

            <div class="col-md-7">
                <div class="wallet-box">
                    <h4 class="mb-3">Minha carteira</h4>
                    <div class="row">

                        <div class="col-md-6">
                            <p>Saldo em conta</p>
                            <h1>R$ <span id="balance">0</span></h1>
                        </div>

                        <div class="col-md-6" style="text-align: right">
                            <p>Saldo bloqueado</p>
                            <h3>R$ <span id="blocked_balance">0</span></h3>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12" style="text-align: center;margin: 20px 0;">
                            <a href="transferir.php" id="btn-transfer">
                                Transferir
                            </a>
                            <a style="cursor: pointer;margin: 0 10px" id="btn-unblocked">
                                Desbloquear
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="account-data">
                    <h5 class="mb-3">Minha conta</h5>
                    <p><strong>Agência:</strong> <span id="agency_account">0</span></p>
                    <p><strong>Conta:</strong> <span id="account_number">0</span></p>
                </div>
            </div>
        </div>

        <div class="box-logs-01">
            <div class="">
                <ul id="log-content-transactions"></ul>
            </div>
        </div>

    </section>



</main>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="inc/js/other.js"></script>

</body>
</html>