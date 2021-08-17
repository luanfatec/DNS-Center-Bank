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


        <!-- Others -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link href="inc/css/globalCss.css">

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
    }

    #section-body::-webkit-scrollbar {
      display: none;
    }

    .greeting-user p {
      font-size: 1.3rem;
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

    .box-transf {
      margin-top: 20px;
      padding: 25px 55px;
      padding-bottom: 15px;
      box-shadow: 0px 4px 11px rgba(0, 0, 0, .5);
      border-radius: 10px;
    }

    #value-target::-webkit-outer-spin-button, #value-target::-webkit-inner-spin-button,
    #agency-target::-webkit-outer-spin-button, #agency-target::-webkit-inner-spin-button,
    #account-target::-webkit-outer-spin-button, #account-target::-webkit-inner-spin-button {
      display: none;
      -webkit-appearance: none;
      margin: 0;
    }

    .account-data {
        text-align: center;
        padding-top: 25px;
        padding-bottom: 18px;
        box-shadow: 0px 4px 11px rgba(0, 0, 0, .5);
        border-radius: 10px;
    }

    #tensfer-value, .btn-cancel {
      width: 40%;
      background: #F29F05;
      border-radius: 10px;
      padding: 5px 28px;
      color: #011526;
      border: none;
      text-decoration: none;
    }

    .btn-cancel {
      margin-left: 100px;
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

      <div class="col-md-10">
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
        </div>
      </div>

        <div class="col-md-2">
          <div class="account-data">
            <h5 class="mb-3">Minha conta</h5>
            <p><strong>Agência:</strong> <span id="agency_account">0</span></p>
            <p><strong>Conta:</strong> <span id="account_number">0</span></p>
          </div>
        </div>

      <div class="col-md-12" style="text-align: center">
        <div class="box-transf">

          <form action="transferir.php" class="row">

            <div class="form-group col-md-4">
              <label for="value-target">Valor a transferir</label>
              <input type='text' class="form-control" id="value-target">
            </div>

            <div class="form-group col-md-4">
              <label for="agency-target">Agência</label>
              <input type="number" class="form-control" id="agency-target">
            </div>

            <div class="form-group col-md-4">
              <label for="account-target">Conta</label>
              <input type="number" class="form-control" id="account-target">
            </div>

            <div class="form-group col-md-12 mt-5">
              <button type="submit" class="btn" id="tensfer-value">Transferir</button>
              <a href="painel.php" class="btn btn-cancel">Cancelar</a>
            </div>

          </form>

        </div>
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