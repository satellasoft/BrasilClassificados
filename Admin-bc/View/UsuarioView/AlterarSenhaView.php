<?php
require_once ("../Controller/UsuarioController.php");
$usuarioController = new UsuarioController();

$resultado = "";


if (filter_input(INPUT_POST, "btnGravar", FILTER_SANITIZE_STRING)) {
    if (filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT)) {
        if ($usuarioController->AlterarSenha(filter_input(INPUT_POST, "txtSenha", FILTER_SANITIZE_STRING), filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT))) {
            ?>
            <script>
                document.cookie = "msg=1";
                document.location.href = "?pagina=alterarsenha&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>";
            </script>
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar alterar a senha.</div>";
        }
    }
}
?>
<div id="dvAlterarSenha">
    <h1>Alterar senha</h1>
    <br />
    <!--DIV CADASTRO -->
    <div class="panel panel-default maxPanelWidth">
        <div class="panel-heading">Alterar Senha</div>
        <div class="panel-body">
            <form method="post" id="frmAlterarSenha" name="frmAlterarSenha" novalidate>

                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="txtSenha">Senha <span class="vlSenha"></span></label>
                            <input type="password" class="form-control" id="txtSenha" name="txtSenha" placeholder="*******"  />
                        </div>
                    </div>

                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="txtSenha2">Confirmar senha <span class="vlSenha"></span></label>
                            <input type="password" class="form-control" id="txtSenha2" name="txtSenha2" placeholder="*******" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <p id="pResultado"><?= $resultado; ?></p>
                    </div>
                </div>
                <input class="btn btn-success" type="submit" name="btnGravar" value="Gravar">
                <a href="?pagina=alterarsenha&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>" class="btn btn-danger">Cancelar</a>

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

    <br /><br />
</div>

<script>
    $(document).ready(function () {

        if (getCookie("msg") == 1) {
            document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Senha alterada com sucesso.</div>";
            document.cookie = "msg=d";
        }

        $("#frmAlterarSenha").submit(function (e) {
            if (!ValidarFormulario()) {
                e.preventDefault();
            }
        });


        var vlSenhas = document.getElementsByClassName("vlSenha");

        $("#txtSenha").keyup(function () {
            if (ValidarSenha()) {
                for (var i = 0; i < vlSenhas.length; i++) {
                    vlSenhas[i].style.color = "green";
                    vlSenhas[i].innerHTML = "válido";
                }
            } else {
                for (var i = 0; i < vlSenhas.length; i++) {
                    vlSenhas[i].style.color = "red";
                    vlSenhas[i].innerHTML = "inválido";
                }
            }
        });

        $("#txtSenha2").keyup(function () {

            if (ValidarSenha()) {
                for (var i = 0; i < vlSenhas.length; i++) {
                    vlSenhas[i].style.color = "green";
                    vlSenhas[i].innerHTML = "válido";
                }
            } else {
                for (var i = 0; i < vlSenhas.length; i++) {
                    vlSenhas[i].style.color = "red";
                    vlSenhas[i].innerHTML = "inválido";
                }
            }
        });
    });

    function ValidarSenha() {
        var senha1 = $("#txtSenha").val();
        var senha2 = $("#txtSenha2").val();

        if (senha1.length >= 7 && senha2.length >= 7) {
            if (senha1 == senha2) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    function ValidarFormulario() {
        var erros = 0;
        var ulErros = document.getElementById("ulErros");
        ulErros.style.color = "red";
        ulErros.innerHTML = "";



        if (!ValidarSenha()) {
            var li = document.createElement("li");
            li.innerHTML = "- Senhas inválidas";
            $("#ulErros").append(li);
            erros++;
        }

        if (erros === 0) {
            return true;
        } else {
            return false;
        }
    }
</script>