<?php
require_once ("../Model/Telefone.php");
require_once ("../Controller/TelefoneController.php");

$telefoneController = new TelefoneController();

$endCod = 0;
$numero = "";
$tipo = 1;
$resultado = "";


//DELETAR
if (filter_input(INPUT_GET, "delcod", FILTER_SANITIZE_NUMBER_INT)) {

    if ($telefoneController->Deletar(filter_input(INPUT_GET, "delcod", FILTER_SANITIZE_NUMBER_INT))) {
        ?>
        <script>
            document.cookie = "msg=3";
            document.location.href = "?pagina=telefone&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>";
        </script>
        <?php
    } else {
        $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar deletar o telefone.</div>";
    }
}


//EDITAR E CADASTRAR
if (filter_input(INPUT_POST, "btnGravar", FILTER_SANITIZE_STRING)) {
    $telefone = new Telefone();

    $telefone->setCod(filter_input(INPUT_GET, "endcod", FILTER_SANITIZE_NUMBER_INT));
    $telefone->setNumero(filter_input(INPUT_POST, "txtNumero", FILTER_SANITIZE_STRING));
    $telefone->setTipo(filter_input(INPUT_POST, "slTipo", FILTER_SANITIZE_STRING));
    $telefone->getUsuario()->setCod(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));

    if (!filter_input(INPUT_GET, "endcod", FILTER_SANITIZE_NUMBER_INT)) {
        //Cadastrar

        if ($telefoneController->Cadastrar($telefone)) {
            ?>
            <script>
                document.cookie = "msg=1";
                document.location.href = "?pagina=telefone&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>";
            </script>
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar cadastrar o telefone.</div>";
        }
    } else {
        //Editar

        if ($telefoneController->Alterar($telefone)) {
            ?>
            <script>
                document.cookie = "msg=2";
                document.location.href = "?pagina=telefone&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>";
            </script>
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar alterar o telefone.</div>";
        }
    }
}


//CONSULTA POR CÓDIGO
if (filter_input(INPUT_GET, "endcod", FILTER_SANITIZE_NUMBER_INT)) {

    $telefone = $telefoneController->RetornarCod(filter_input(INPUT_GET, "endcod", FILTER_SANITIZE_NUMBER_INT));

    if ($telefone != null) {
        $numero = $telefone->getNumero();
        $tipo = $telefone->getTipo();
    }
}


//CONSULTA COMPLETA
$listaTelefone = $telefoneController->RetornarTodosUsuario(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));
?>

<div id="dvTelefoneView">
    <h1>Gerenciar Telefone</h1>
    <br />
    <!--DIV CADASTRO -->
    <div class="panel panel-default maxPanelWidth">
        <div class="panel-heading">Cadastrar e editar</div>
        <div class="panel-body">
            <form method="post" id="frmGerenciarTelefone" name="frmGerenciarTelefone" novalidate>

                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <input type="hidden" id="txtCodTelefone" name="txtCodTelefone" value="<?= $endCod; ?>" />
                            <label for="txtNumero">Número </label>
                            <input type="text" class="form-control" id="txtNumero" name="txtNumero" value="<?= $numero; ?>" />
                        </div>
                    </div>

                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="slTipo">Tipo</label>
                            <select class="form-control" id="slTipo" name="slTipo">
                                <option value="1" <?= ($tipo == 1 ? "selected='selected'" : ""); ?>>Celular</option> 
                                <option value="2" <?= ($tipo == 2 ? "selected='selected'" : ""); ?>>Telefone</option> 
                                <option value="3" <?= ($tipo == 3 ? "selected='selected'" : ""); ?>>Fax</option> 
                            </select>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <p id="pResultado"><?= $resultado; ?></p>
                    </div>
                </div>
                <input class="btn btn-success" type="submit" name="btnGravar" value="Gravar">
                <a href="?pagina=telefone&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>" class="btn btn-danger">Cancelar</a>

                <br />
                <br />
                <div class="row">
                    <div class="col-lg-12">
                        <ul id="ulErros"></ul>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <br />
    <br />

    <!--##########################################
    ###########################################-->
    <div class="panel panel-info maxPanelWidth">
        <div class="panel-heading">Consulta</div>
        <div class="panel-body">
            <table class="table table-hover table-responsive table-striped">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Tipo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($listaTelefone != null) {
                        foreach ($listaTelefone as $telefone) {

                            $tipo = "";

                            if ($telefone->getTipo() == 1) {
                                $tipo = "Celular";
                            } else if ($telefone->getTipo() == 2) {
                                $tipo = "Telefone";
                            } else {
                                $tipo = "Fax";
                            }
                            ?>
                            <tr>
                                <td><?= $telefone->getNumero() ?></td>
                                <td><?= $tipo; ?></td>
                                <td>
                                    <a class="btn btn-warning" href="?pagina=telefone&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>&endcod=<?= $telefone->getCod(); ?>">Editar</a>
                                    <a onclick="return confirm('Deseja deletar o número?');" class="btn btn-danger" href="?pagina=telefone&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>&delcod=<?= $telefone->getCod(); ?>">Deletar</a>
                                </td>
                            </tr>

                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="../js/mask.js" type="text/javascript"></script>

<script>
    $(document).ready(function () {
        $('#txtNumero').mask('(00) 00000-0000');

        if (getCookie("msg") == 1) {
            document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Telefone cadastrado com sucesso.</div>";
            document.cookie = "msg=d";
        } else if (getCookie("msg") == 2) {
            document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Telefone alterado com sucesso.</div>";
            document.cookie = "msg=d";
        } else if (getCookie("msg") == 3) {
            document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Telefone deletado com sucesso.</div>";
            document.cookie = "msg=d";
        }


        $("#frmGerenciarTelefone").submit(function (event) {
            if (!Validar()) {
                event.preventDefault();
            }
        });
    });


    function Validar() {
        var erros = 0;

        var ulErros = document.getElementById("ulErros");
        ulErros.style.color = "red";
        ulErros.innerHTML = "";

        if ($("#slTipo").val() <= 0 || $("#slTipo").val() > 3) {
            erros++;
            var li = document.createElement("li");
            li.innerHTML = "Tipo de número inválido";
            ulErros.appendChild(li);
        }

        if ($("#txtNumero").val().length < 5) {
            erros++;
            var li = document.createElement("li");
            li.innerHTML = "Número inválido";
            ulErros.appendChild(li);
        }

        if (erros === 0) {
            return true;
        } else {
            return false;
        }
    }
</script>