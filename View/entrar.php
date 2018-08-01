<?php
$resultado = "";
?>
<div id="dvEntrar">
    <h1>Entrar</h1>
    <br />
    <div id="dvFormularioEntrar" class="formcontroles">
        <form method="post" name="frmEntrar" id="frmEntrar">
            <!--2º row-->
            <div class="linha">
                <div class="grid-50 mobile-grid-100 coluna">
                    <label for="txtUsuario">Usuário  <span id="spValidaUsuario"></span></label>
                    <input type="text" id="txtUsuario" name="txtUsuario" placeholder="usuario"  />
                </div>

                <div class="grid-50 mobile-grid-100 coluna">
                    <label for="txtSenha">Senha <span class="spValidaSenha"></span></label>
                    <input type="password" id="txtSenha" name="txtSenha" placeholder="*********" />
                </div>
            </div>
            <div class="clear"></div>

            <!--5º row-->
            <div class="linha">
                <div class="grid-80 mobile-grid-100 coluna right link">
                    <a href="">Esqueci minha senha</a> |
                    <a href="?pagina=cadastro">Cadastrar</a>
                </div>

                <div class="grid-20 mobile-grid-100 coluna">
                    <input type="submit" name="btnSubmit" id="btnSubmit" value="Entrar" class="btn-padrao" />
                </div>
            </div>
            <div class="clear"></div>

            <div class="linha">
                <div class="grid-100 coluna">
                    <span id="spResultado"><?= $resultado; ?></span>
                </div>
            </div>
        </form>
        <div class="clear"></div>
        <br />
    </div>
</div>
<script>
    $("#frmEntrar").submit(function (event) {
        if (Validar()) {
            Autenticar();
        }
        event.preventDefault();
    });

    function Validar() {
        var erros = 0;
        $("#spResultado").text("");
        $("#spResultado").css("color", "red");

        if ($("#txtUsuario").val().length < 7) {
            $("#spResultado").html("<span class='spErro'>Usuário inválido. (min. 7 caracteres)</span>");
            erros++;
        } else {
            if ($("#txtSenha").val().length < 7) {
                $("#spResultado").html("<span class='spErro'>Senha inválida. (min. 7 caracteres)</span>");
                erros++;
            }
        }

        if (erros == 0) {
            return true;
        } else {
            return false;
        }
    }


    function Autenticar() {
        var obj = {
            txtUsuario: $("#txtUsuario").val(),
            txtSenha: $("#txtSenha").val()
        };

        console.log(obj);

        $.ajax({
            url: "Action/UsuarioAction.php?req=4",
            type: "POST",
            dataType: "text",
            data: obj,
            beforeSend: function () {
                $("#spResultado").text("Autenticando...");
                $("#spResultado").css("color", "blue");
            },
            success: function (retorno) {
                console.log(retorno);
                if (retorno == "ok") {
                    $("#spResultado").css("color", "green");
                    $("#spResultado").html("<span class='spSucesso'>Redirecionado...</span>");
                    location.href = "?pagina=home";
                } else {
                    $("#spResultado").html("<span class='spErro'>Usuário ou senha inválido</span>");
                }
            },
            error: function (erro) {
                console.log(erro);
            }
        });
    }
</script>
