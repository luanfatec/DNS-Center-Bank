<?php
session_start();
if (!isset($_SESSION["hash_pay"])) {
    header("location:index.php");
}

$msg = "off";
$message = "";
$status = "";
if (isset($_GET['message']) and isset($_GET['status'])) {
    $message = htmlentities($_GET['message']);
    $status = htmlentities($_GET['status']);
    $msg = 'on';
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


        .line-logs span {
            position: relative;
            color: #022340;
            text-decoration: underline;
            font-weight: bold;
            margin-top: 2px;
        }


        #inp-select-image-profile {
            width: 90%;
        }

        .btn-actualize {
            background: #F29F05;
            border-radius: 10px;
            margin-top: 10px;
            padding: 5px 28px;
            color: #011526;
            border: none;
            text-decoration: none;
        }

        input[type="update_data_access"] {
            //
        }

        input#number::-webkit-outer-spin-button,
        input#number::-webkit-inner-spin-button,
        input#number::-webkit-inner-spin-button:hover
        {
            -webkit-appearance: none;
        }

        input#number {
            -moz-appearance: textfield;
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

        <form class="row" action="api.php" id="form-data-personal" method="post" enctype="multipart/form-data">

            <div class="col-md-4">
                <div class="profile-box p-5">

                    <div>
                        <div>
                            <img class="img-box-profile border-gray" src=<?php echo "inc/profile/".str_replace("-", ".", $_SESSION["url_profile"])?> width="100" align="center">
                        </div>
                    </div>

                    <div class="profile-body">
                        <h4><?=$_SESSION['name']?></h4>

                        <div class="form-group mt-5">
                            <input type="file" class="form-control" id="inp-select-image-profile" name="img_profile">
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-8">
                <div class="wallet-box">

                    <div class="row">

                        <div class="col-md-12">
                            <h4 class="mb-3">Dados Pesssoais</h4>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="name">Nome:</label>
                            <input type="text" class="form-control" name="name" id="name" value="<?=$_SESSION['name']?>">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="age">Idade:</label>
                            <input type="text" class="form-control" name="age" id="age" value="21">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="data_birth">Data de Nascimento:</label>
                            <input type="date" class="form-control" name="data_birth" id="data_birth" value="1999-11-14">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="document">CPF:</label>
                            <input type="text" class="form-control" name="document" id="document" value="47659873881">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="ident">RG:</label>
                            <input type="text" class="form-control" name="ident" id="ident" value="567311569">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="sex-select">Sexo:</label>
                            <select class="form-control" id="sex-select" name="sex-select">
                                <option value>Selecione</option>
                                <option value="masc">Masculino</option>
                                <option value="femn">Feminino</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <h4 class="mb-3">Endereço</h4>
                        </div>

                        <div class="col-md-5 form-group">
                            <label for="zip_code">Cep:</label>
                            <input type="text" class="form-control" name="zip_code" id="zip_code" onchange="checkZipCode()">
                        </div>

                        <div class="col-md-5 form-group">
                            <label for="public_place">Logradouro:</label>
                            <input type="text" class="form-control" name="public_place" id="public_place">
                        </div>

                        <div class="col-md-2 form-group">
                            <label for="number">Numero:</label>
                            <input type="number" class="form-control" name="number" id="number">
                        </div>

                        <div class="col-md-5 form-group">
                            <label for="district">Bairro:</label>
                            <input type="text" class="form-control" name="district" id="district">
                        </div>

                        <div class="col-md-5 form-group">
                            <label for="city">Cidade:</label>
                            <input type="text" class="form-control" name="city" id="city">
                        </div>

                        <div class="col-md-2 form-group">
                            <label for="state-select">Estado</label>
                            <input type="text" class="form-control" max="2" min="1" name="state-select" id="state-select">
                        </div>

                        <div class="col-md-12 ">
                            <h4 class="mb-3">Dados de acesso</h4>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="">E-mail:</label>
                            <input type="email" class="form-control" name="email" value="<?= $_SESSION['email']?>">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="">Senha:</label>
                            <input type="password" class="form-control" name="password">
                        </div>

                        <div class="col-md-2 form-group mt-4" style="text-align: center">
                            <button type="submit" class="btn btn-actualize" id="btn-save-data-personal">Atualizar</button>
                        </div>

                        <div class="col-md-2 form-group mt-4" style="text-align: center">
                            <a href="painel.php" class="btn btn-actualize">Cancelar</a>
                        </div>


                        <div class="col-12 form-check">
                            <input class="form-check-input" id="update_data_access_id" type="checkbox" name="update_data_access" />
                            <label class="form-check-label" for="update_data_access_id">
                                Confirmar atualização dos dados de acesso.
                            </label>
                        </div>


                    </div>

                </div>
            </div>

        </form>


    </section>


</main>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="inc/js/other.js"></script>
<script>
    let msg = "<?php echo $msg;?>"
    if (msg === 'on') {
        Notify({message: "<?php echo $message?>", color: "red"});
    }
</script>
</body>
</html>