<?php
require_once("Model/Usuario.php");
require_once("Controller/UsuarioController.php");

$resultado = "";

$erros = [];

if (filter_input(INPUT_POST, "btnSubmit", FILTER_SANITIZE_STRING)) {
    $usuarioController = new UsuarioController();

    $erros = Validar();

    if (empty($erros)) {

        $usuario = new Usuario();

        $usuario->setNome(filter_input(INPUT_POST, "txtNome", FILTER_SANITIZE_STRING));
        $usuario->setEmail(filter_input(INPUT_POST, "txtEmail", FILTER_SANITIZE_STRING));
        $usuario->setUsuario(filter_input(INPUT_POST, "txtUsuario", FILTER_SANITIZE_STRING));
        $usuario->setCpf(filter_input(INPUT_POST, "txtCpf", FILTER_SANITIZE_STRING));
        $usuario->setSenha(filter_input(INPUT_POST, "txtSenha", FILTER_SANITIZE_STRING));
        $usuario->setNascimento(filter_input(INPUT_POST, "txtData", FILTER_SANITIZE_STRING));
        $usuario->setSexo(filter_input(INPUT_POST, "slSexo", FILTER_SANITIZE_STRING));
        $usuario->setStatus(1);
        $usuario->setPermissao(2);

        if ($usuarioController->Cadastrar($usuario)) {
            $resultado = "<span class='spSucesso'>Usuário cadastrado com sucesso!</span>";
        } else {
            $resultado = "<span class='spErro'>Houve um erro ao tentar cadastrar o usuário!</span>";
        }
    }
}

function Validar() {
    $listaErros = [];
    $usuarioController = new UsuarioController();

    if (strlen(filter_input(INPUT_POST, "txtNome", FILTER_SANITIZE_STRING)) < 5) {
        $listaErros[] = "- Nome inválido. (min 5 caracteres)";
    }

    if (strlen(filter_input(INPUT_POST, "txtUsuario", FILTER_SANITIZE_STRING)) >= 7) {
        if ($usuarioController->VerificaUsuarioExiste(filter_input(INPUT_POST, "txtUsuario", FILTER_SANITIZE_STRING)) == 1) {
            $listaErros[] = "- Usuário já cadastrado.";
        }
    } else {
        $listaErros[] = "- Usuário inválido. (min 7 caracteres)";
    }

    if (filter_input(INPUT_POST, "txtEmail", FILTER_SANITIZE_EMAIL)) {
        if ($usuarioController->VerificaEmailExiste(filter_input(INPUT_POST, "txtEmail", FILTER_SANITIZE_STRING)) == 1) {
            $listaErros[] = "- E-mail já cadastrado.";
        }
    } else {
        $listaErros[] = "- E-mail inválido.";
    }

    if (strlen(filter_input(INPUT_POST, "txtCpf", FILTER_SANITIZE_STRING)) == 14) {
        if ($usuarioController->VerificaCPFExiste(filter_input(INPUT_POST, "txtCpf", FILTER_SANITIZE_STRING)) == 1) {
            $listaErros[] = "- CPF já cadastrado.";
        }
    } else {
        $listaErros[] = "- CPF inválido.";
    }

    if (filter_input(INPUT_POST, "txtSenha", FILTER_SANITIZE_STRING) != filter_input(INPUT_POST, "txtSenha2", FILTER_SANITIZE_STRING) || strlen(filter_input(INPUT_POST, "txtSenha", FILTER_SANITIZE_STRING) < 7)) {
        $listaErros[] = "- Senhas inválidas. (min 7 caracteres)";
    }

    if (filter_input(INPUT_POST, "txtData", FILTER_SANITIZE_STRING) == "") {
        $listaErros[] = "- Data inválida";
    }
    if (filter_input(INPUT_POST, "slSexo", FILTER_SANITIZE_STRING) != "m") {
        if (filter_input(INPUT_POST, "slSexo", FILTER_SANITIZE_STRING) != "f") {
            $listaErros[] = "- Sexo inválido.";
        }
    }

    return $listaErros;
}
?>
<div id="dvCadastro">
    <h1>Cadastre-se grátis</h1>
    <br />

    <div id="dvFormCadastro" class="formcontroles">
        <form method="post" name="frmCadastro" id="frmCadastro">
            <!--1º row-->
            <div class="linha">
                <div class="grid-50 mobile-grid-100 coluna">
                    <label for="txtNome">Nome</label>
                    <input type="text" id="txtNome" name="txtNome" placeholder="Nome completo" />
                </div>

                <div class="grid-50 mobile-grid-100 coluna">
                    <label for="txtEmail">E-mail  <span id="spValidaEmail">&nbsp;</span></label>
                    <input type="text" id="txtEmail" name="txtEmail" placeholder="email@dominio.com" />
                </div>
            </div>
            <div class="clear"></div>

            <!--2º row-->
            <div class="linha">
                <div class="grid-50 mobile-grid-100 coluna">
                    <label for="txtUsuario">Usuário  <span id="spValidaUsuario"></span></label>
                    <input type="text" id="txtUsuario" name="txtUsuario" placeholder="usuario" />
                </div>

                <div class="grid-50 mobile-grid-100 coluna">
                    <label for="txtCpf">CPF <span id="spValidaCpf">&nbsp;</span></label>
                    <input type="text" id="txtCpf" name="txtCpf" placeholder="000.000.000-90" />
                </div>
            </div>
            <div class="clear"></div>

            <!--3º row-->
            <div class="linha">
                <div class="grid-50 mobile-grid-100 coluna">
                    <label for="txtSenha">Senha <span class="spValidaSenha"></span></label>
                    <input type="password" id="txtSenha" name="txtSenha" placeholder="*********" />
                </div>

                <div class="grid-50 mobile-grid-100 coluna">
                    <label for="txtSenha2">Confirmar senha <span class="spValidaSenha"></span></label>
                    <input type="password" id="txtSenha2" name="txtSenha2" placeholder="*********" />
                </div>
            </div>
            <div class="clear"></div>

            <!--4º row-->
            <div class="linha">
                <div class="grid-50 mobile-grid-100 coluna">
                    <label for="txtData">Data nascimento</label>
                    <input type="text" id="txtData" name="txtData" placeholder="21/08/1992" />
                </div>

                <div class="grid-50 mobile-grid-100 coluna">
                    <label for="slSexo">Sexo</label>
                    <select name="slSexo" id="slSexo">
                        <option value="m">Masculino</option>
                        <option value="f">Feminino</option>
                    </select>
                </div>
            </div>
            <div class="clear"></div>

            <!--5º row-->
            <div class="linha">
                <div class="grid-100 coluna">
                    <input type="submit" name="btnSubmit" id="btnSubmit" value="Cadastrar" class="btn-padrao" />
                </div>
            </div>
        </form>
        <div class="clear"></div>

        <!--6º row-->
        <div class="linha">
            <div class="grid-100 coluna">
                <span id="spResultado"><?= $resultado; ?></span>
            </div>
        </div>
        <div class="clear"></div>

        <!--7º row-->
        <div class="linha">
            <div class="grid-100 coluna">
                <ul id="ulErros" style="list-style: none;">
                    <?php
                    foreach ($erros as $e) {
                        ?>
                        <li><?= $e; ?></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="clear"></div>
        <br />
    </div>
</div>
<script src="js/mask.js" type="text/javascript"></script>
<script>
    $('#txtCpf').mask('000.000.000-00'); //CEP
    $('#txtData').mask('00/00/0000'); //CEP

    $("#txtSenha, #txtSenha2").keyup(function () {
        var resultadoValidacao = ValidarSenha(7);
        ExibeMensagens(resultadoValidacao);
    });
    $("#txtUsuario").focusout(function () {
        ValidaUsuario();
    });
    $("#txtEmail").focusout(function () {
        ValidaEmail();
    });
    $("#txtCpf").focusout(function () {
        {
            if (ValidaCPF($("#txtCpf").val())) {
                ValidaCPFBanco();
            } else {
                $("#spValidaCpf").css("color", "#FF3730");
                $("#spValidaCpf").text("CPF inválido");
            }
        }
    });
    $("#txtCpf").keyup(function () {
        if (ValidaCPF($("#txtCpf").val())) {
            $("#spValidaCpf").css("color", "#39C462");
            $("#spValidaCpf").text("CPF válido");
        } else {
            $("#spValidaCpf").css("color", "#FF3730");
            $("#spValidaCpf").text("CPF inválido");
        }
    });
    $("#frmCadastro").submit(function (event) {
        if (!Validar()) {
            event.preventDefault();
        }
    });
    function Validar() {
        var erros = 0;

        var ulErros = document.getElementById("ulErros");
        ulErros.innerHTML = "";
        ulErros.style.color = "red";
        ulErros.style.listStyle = "none";

        if ($("#txtNome").val().length < 5) {
            var li = document.createElement("li");
            li.innerText = "- Nome inválido (min. 5 caracteres)";
            ulErros.appendChild(li);
            erros++;
        }

        if ($("#txtUsuario").val().length < 7) {
            var li = document.createElement("li");
            li.innerText = "- Usuário inválido (min. 7 caracteres)";
            ulErros.appendChild(li);
            erros++;
        }

        if ($("#txtEmail").val().indexOf("@") <= 0 || $("#txtEmail").val().indexOf(".") <= 0) {
            var li = document.createElement("li");
            li.innerText = "- E-mail inválido";
            ulErros.appendChild(li);
            erros++;
        }

        if (!ValidarSenha(7)) {
            var li = document.createElement("li");
            li.innerText = "- Senhas inválidas";
            ulErros.appendChild(li);
            erros++;
        }

        if (!ValidaCPF($("#txtCpf").val())) {
            var li = document.createElement("li");
            li.innerText = "- Senhas inválidas";
            ulErros.appendChild(li);
            erros++;
        }

        if (!ValidarData($("#txtData").val())) {
            var li = document.createElement("li");
            li.innerText = "- Data de nascimento inválida";
            ulErros.appendChild(li);
            erros++;
        }

        if (erros == 0) {
            return true;
        } else {
            return false;
        }
    }

    function ValidarData(data) {
        var dt = new Date();
        //21/08/1992
        var arrData = data.split("/");
        console.log((dt.getFullYear() - 80));
        if (arrData[0] > 0 && arrData[0] <= 31) {
            if (arrData[1] > 0 && arrData[1] <= 12) {
                if (arrData[2] > (dt.getFullYear() - 80) && arrData[1] <= dt.getFullYear()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ValidarSenha(minLenght) {
        var senha1 = document.getElementById("txtSenha").value;
        var senha2 = document.getElementById("txtSenha2").value;
        var valido = false;
        if (senha1.length >= minLenght && senha2.length >= minLenght) {
            if (senha1 === senha2) {
                valido = true;
            }
        }

        return valido;
    }

    function ExibeMensagens(valido) {
        var spValidaSenha = document.getElementsByClassName("spValidaSenha");
        if (valido) {
            for (var i = 0; i < spValidaSenha.length; i++) {
                spValidaSenha[i].style.color = "#39C462";
                spValidaSenha[i].innerText = "Senhas corretas";
            }
        } else {
            for (var i = 0; i < spValidaSenha.length; i++) {
                spValidaSenha[i].style.color = "#FF3730";
                spValidaSenha[i].innerText = "Senhas inválidas";
            }
        }
    }

    //https://www.satellasoft.com/?scripter=a1p6y6
    //https://pt.stackoverflow.com/questions/170/como-fazer-replaceall-no-javascript
    String.prototype.replaceAll = String.prototype.replaceAll || function (needle, replacement) {
        return this.split(needle).join(replacement);
    };
//http://www.devmedia.com.br/validar-cpf-com-javascript/23916
    function ValidaCPF(strCPF) {
        var arrayNumerosInvalidos = ["11111111111", "22222222222", "33333333333", "44444444444", "55555555555", "66666666666", "77777777777", "88888888888", "99999999999"];
        var CPFDigitosValid = true;
        strCPF = strCPF.replaceAll(".", "");
        strCPF = strCPF.replaceAll("-", "");
        strCPF.trim(); //remover espaços

        for (var i = 0; i < arrayNumerosInvalidos.length; i++) {
            if (strCPF == arrayNumerosInvalidos[i]) {
                CPFDigitosValid = false;
            }
        }

        if (CPFDigitosValid) {

            //https://www.w3schools.com/jsref/jsref_trim_string.asp

            var Soma;
            var Resto;
            Soma = 0;
            if (strCPF == "00000000000")
                return false;
            for (i = 1; i <= 9; i++)
                Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
            Resto = (Soma * 10) % 11;
            if ((Resto == 10) || (Resto == 11))
                Resto = 0;
            if (Resto != parseInt(strCPF.substring(9, 10)))
                return false;
            Soma = 0;
            for (i = 1; i <= 10; i++)
                Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
            Resto = (Soma * 10) % 11;
            if ((Resto == 10) || (Resto == 11))
                Resto = 0;
            if (Resto != parseInt(strCPF.substring(10, 11)))
                return false;
            return true;
        } else {
            return false;
        }
    }

    //Validações AJAX
    function ValidaUsuario() {
        var usuario = $("#txtUsuario").val();
        if (usuario.length >= 3) {
            $.ajax({
                url: "Action/UsuarioAction.php?req=1",
                data: {txtUsuario: $("#txtUsuario").val()},
                type: "POST",
                dataType: "text",
                success: function (retorno) {
                    if (retorno == -1) {
                        $("#spValidaUsuario").text("Usuário válido");
                        $("#spValidaUsuario").css("color", "#39C462");
                    } else if (retorno == 1) {
                        $("#spValidaUsuario").text("Usuário já cadastrado ");
                        $("#spValidaUsuario").css("color", "#FF4500");
                    } else {
                        $("#spValidaUsuario").text("Erro ao válidar");
                        $("#spValidaUsuario").css("color", "#FF3730");
                    }
                },
                error: function (erro) {
                    console.log(erro);
                }
            });
        } else {
            return -10;
        }
    }

    function ValidaEmail() {

        var email = $("#txtEmail").val();
        if (email.indexOf("@") > 0 && email.indexOf(".") > 0) {
            $.ajax({
                url: "Action/UsuarioAction.php?req=2",
                data: {txtEmail: $("#txtEmail").val()},
                type: "POST",
                dataType: "text",
                success: function (retorno) {
                    if (retorno == -1) {
                        $("#spValidaEmail").text("E-mail válido");
                        $("#spValidaEmail").css("color", "#39C462");
                    } else if (retorno == 1) {
                        $("#spValidaEmail").text("E-mail já cadastrado ");
                        $("#spValidaEmail").css("color", "#FF4500");
                    } else {
                        $("#spValidaEmail").text("Erro ao válidar");
                        $("#spValidaEmail").css("color", "#FF3730");
                    }
                },
                error: function (erro) {
                    console.log(erro);
                }
            });
        } else {
            return -10;
        }
    }

    function ValidaCPFBanco() {

        var cpf = $("#txtCpf").val();
        ;
        if (cpf.length == 14) {
            $.ajax({
                url: "Action/UsuarioAction.php?req=3",
                data: {txtCPF: $("#txtCpf").val()},
                type: "POST",
                dataType: "text",
                success: function (retorno) {
                    if (retorno == -1) {
                        $("#spValidaCpf").text("CPF não está em uso");
                        $("#spValidaCpf").css("color", "#39C462");
                    } else if (retorno == 1) {
                        $("#spValidaCpf").text("CPF já cadastrado ");
                        $("#spValidaCpf").css("color", "#FF4500");
                    } else {
                        $("#spValidaCpf").text("Erro ao válidar");
                        $("#spValidaCpf").css("color", "#FF3730");
                    }

                    console.log(retorno);
                },
                error: function (erro) {
                    console.log(erro);
                }
            });
        } else {
            return -10;
        }
    }
</script>