<?php
require_once ("../Controller/UsuarioController.php");
require_once ("../Model/Usuario.php");

$usuarioController = new UsuarioController();

$cod = 0;
$nome = "";
$email = "";
$usuario = "";
$cpf = "";
$senha = "";
$dtNascimento = "";
$sexo = "m";
$permissao = 1;
$status = 1;

$resultado = "";
$spResultadoBusca = "";
$listaUsuariosBusca = [];

if (filter_input(INPUT_POST, "btnGravar", FILTER_SANITIZE_STRING)) {
    $usuario = new Usuario();

    $usuario->setNome(filter_input(INPUT_POST, "txtNome", FILTER_SANITIZE_STRING));
    $usuario->setEmail(filter_input(INPUT_POST, "txtEmail", FILTER_SANITIZE_STRING));
    $usuario->setCpf(filter_input(INPUT_POST, "txtCpf", FILTER_SANITIZE_STRING));
    $usuario->setUsuario(filter_input(INPUT_POST, "txtUsuario", FILTER_SANITIZE_STRING));
    $usuario->setSenha(filter_input(INPUT_POST, "txtSenha", FILTER_SANITIZE_STRING));
    $usuario->setNascimento(filter_input(INPUT_POST, "txtData", FILTER_SANITIZE_STRING));
    $usuario->setSexo(filter_input(INPUT_POST, "slSexo", FILTER_SANITIZE_STRING));
    $usuario->setStatus(filter_input(INPUT_POST, "slStatus", FILTER_SANITIZE_NUMBER_INT));
    $usuario->setPermissao(filter_input(INPUT_POST, "slPermissao", FILTER_SANITIZE_NUMBER_INT));

    if (!filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT)) {
        //Cadastrar

        if ($usuarioController->Cadastrar($usuario)) {
            ?>
            <script>
                document.cookie = "msg=1";
                document.location.href = "?pagina=usuario";
            </script>
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar cadastrar o usuário.</div>";
        }
    } else {
        //Editar
        $usuario->setCod(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));

        if ($usuarioController->Alterar($usuario)) {
            ?>
            <script>
                document.cookie = "msg=2";
                document.location.href = "?pagina=usuario";
            </script>
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar alterar o usuário.</div>";
        }
    }
}

//Buscar usuários

if (filter_input(INPUT_POST, "btnBuscar", FILTER_SANITIZE_STRING)) {

    $termo = filter_input(INPUT_POST, "txtTermo", FILTER_SANITIZE_STRING);
    $tipo = filter_input(INPUT_POST, "slTipoBusca", FILTER_SANITIZE_NUMBER_INT);
    $listaUsuariosBusca = $usuarioController->RetornarUsuarios($termo, $tipo);

    if ($listaUsuariosBusca != null) {
        $spResultadoBusca = "Exibindo dados";
    } else {
        $spResultadoBusca = "Dados não encontrado";
    }
}

if (filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT)) {
    $retornoUsuario = $usuarioController->RetornaCod(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));

    $cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
    $nome = $retornoUsuario->getNome();
    $email = $retornoUsuario->getEmail();
    $usuario = $retornoUsuario->getUsuario();
    $cpf = $retornoUsuario->getCpf();
    $senha = "sim";
    //--------
    //http://stackoverflow.com/questions/10306999/php-convert-date-format-dd-mm-yyyy-yyyy-mm-dd
    $date = str_replace('-', '/', $retornoUsuario->getNascimento());
    $dtNascimento = date('d-m-Y', strtotime($date));

    $sexo = $retornoUsuario->getSexo();
    $permissao = $retornoUsuario->getPermissao();
    $status = $retornoUsuario->getStatus();
}
?>
<div id="dvUsuarioView">
    <h1>Gerenciar Usuários</h1>
    <br />
    <div class="controlePaginas">
        <a href="?pagina=usuario"><img src="img/icones/editar.png" alt=""/></a>
        <a href="?pagina=usuario&consulta=s"><img src="img/icones/buscar.png" alt=""/></a>
    </div>

    <br />
    <!--DIV CADASTRO -->
    <?php
    if (!filter_input(INPUT_GET, "consulta", FILTER_SANITIZE_STRING)) {
        ?>
        <div class="panel panel-default maxPanelWidth">
            <div class="panel-heading">Cadastrar e editar</div>
            <div class="panel-body">
                <form method="post" id="frmGerenciarUsuario" name="frmGerenciarUsuario" novalidate>

                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <input type="hidden" id="txtCodUsuario" value="<?= $cod; ?>" />
                                <label for="txtNome">Nome completo</label>
                                <input type="text" class="form-control" id="txtNome" name="txtNome" placeholder="Nome completo" value="<?= $nome; ?>">
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="txtUsuario">Usuário</label>
                                <input type="text" class="form-control" id="txtUsuario" name="txtUsuario" placeholder="nomedeusuario"  value="<?= $usuario; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="txtEmail">E-mail</label>
                                <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="email@dominio.com"  value="<?= $email; ?>">
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="txtCpf">CPF</label>
                                <input type="text" class="form-control" id="txtCpf" name="txtCpf" placeholder=""  value="<?= $cpf; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="txtSenha">Senha <span class="vlSenha"></span></label>
                                <input type="password" class="form-control" id="txtSenha" name="txtSenha" placeholder="*******" <?= ($senha) == "" ? "" : "disabled='disabled'"; ?> />
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="txtSenha2">Confirmar senha <span class="vlSenha"></span></label>
                                <input type="password" class="form-control" id="txtSenha2" name="txtSenha2" placeholder="*******" <?= ($senha) == "" ? "" : "disabled='disabled'"; ?> />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="txtData">Data nascimento</label>
                                <input type="text" class="form-control" id="txtData" name="txtData" placeholder="21/08/1992" value="<?= $dtNascimento; ?>"/>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="slSexo">Sexo</label>
                                <select class="form-control" id="slSexo" name="slSexo">
                                    <option value="m" <?= ($sexo == "m" ? "selected='selected'" : "") ?>>Masculino</option>
                                    <option value="f" <?= ($sexo == "f" ? "selected='selected'" : "") ?>>Feminino</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="slStatus">Status</label>
                                <select class="form-control" id="slStatus" name="slStatus">
                                    <option value="1" <?= ($status == "1" ? "selected='selected'" : "") ?>>Ativo</option>
                                    <option value="2" <?= ($status == "2" ? "selected='selected'" : "") ?>>Bloqueado</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="slPermissao">Permissão</label>
                                <select class="form-control" id="slPermissao" name="slPermissao">
                                    <option value="1" <?= ($permissao == "1" ? "selected='selected'" : "") ?>>Administrador</option>
                                    <option value="2" <?= ($permissao == "2" ? "selected='selected'" : "") ?>>Comum</option>
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
                    <a href="#" class="btn btn-danger">Cancelar</a>

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
        <?php
    } else {
        ?>
        <br />
        <!--DIV CONSULTA -->
        <div class="panel panel-default maxPanelWidth">
            <div class="panel-heading">Consultar</div>
            <div class="panel-body">
                <form method="post" name="frmBuscarUsuario" id="frmBuscarUsuario">
                    <div class="row">
                        <div class="col-lg-8 col-xs-12">
                            <div class="form-group">
                                <label for="txtTermo">Termo de busca</label>
                                <input type="text" class="form-control" id="txtTermo" name="txtTermo" placeholder="Ex: fulano de tal" />
                            </div>
                        </div>

                        <div class="col-lg-4 col-xs-12">
                            <div class="form-group">
                                <label for="slTipoBusca">Tipo</label>
                                <select class="form-control" id="slTipoBusca" name="slTipoBusca">
                                    <option value="1">Nome</option>
                                    <option value="2">E-mail</option>
                                    <option value="3">CPF </option>
                                    <option value="4">Usuário </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <input class="btn btn-info" type="submit" name="btnBuscar" value="Buscar"> 
                            <span><?= $spResultadoBusca; ?></span>
                        </div>
                    </div>
                </form>

                <hr />
                <br />

                <table class="table table-responsive table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Usuário</th>
                            <th>Status</th>
                            <th>Permissão</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($listaUsuariosBusca != null) {
                            foreach ($listaUsuariosBusca as $user) {
                                ?>
                                <tr>
                                    <td><?= $user->getNome(); ?></td>
                                    <td><?= $user->getUsuario(); ?></td>
                                    <td><?= ($user->getStatus() == 1 ? "Ativo" : "Bloqueado") ?></td>
                                    <td><?= ($user->getPermissao() == 1 ? "Administrador." : "Comum") ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Opções <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="?pagina=visualizarusuario&cod=<?= $user->getCod(); ?>">Visualizar</a></li>
                                                <li><a href="?pagina=usuario&cod=<?= $user->getCod(); ?>">Editar</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="?pagina=alterarsenha&cod=<?= $user->getCod(); ?>">Alterar senha</a></li>
                                                <li><a href="?pagina=endereco&cod=<?= $user->getCod(); ?>">Gerenciar endereço</a></li>
                                                <li><a href="?pagina=telefone&cod=<?= $user->getCod(); ?>">Gerenciar telefone</a></li>
                                            </ul>
                                        </div>
                                </tr>

                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<script src="../js/mask.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        if (getCookie("msg") == 1) {
            document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Usuário cadastrado com sucesso.</div>";
            document.cookie = "msg=d";
        } else if (getCookie("msg") == 2) {
            document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Usuário alterado com sucesso.</div>";
            document.cookie = "msg=d";
        }

        $('#txtCpf').mask('000.000.000-00');
        $('#txtData').mask('00/00/0000');

        $("#frmGerenciarUsuario").submit(function (e) {
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


        //Javascript nativo
        if (document.getElementById("txtNome").value.length < 5) {
            var li = document.createElement("li");
            li.innerHTML = "- Informe um nome válido";
            ulErros.appendChild(li);
            erros++;
        }

        if (document.getElementById("txtUsuario").value.length < 7) {
            var li = document.createElement("li");
            li.innerHTML = "- Informe um nome de usuário válido";
            ulErros.appendChild(li);
            erros++;
        }

        if (document.getElementById("txtEmail").value.indexOf("@") < 0 || document.getElementById("txtEmail").value.indexOf(".") < 0) {
            var li = document.createElement("li");
            li.innerHTML = "- Informe um e-mail válido";
            ulErros.appendChild(li);
            erros++;
        }

        //JQuery
        if ($("#txtCpf").val().length < 14) {
            var li = document.createElement("li");
            li.innerHTML = "- Informe um CPF válido";
            $("#ulErros").append(li);
            erros++;
        }

        if (!ValidarSenha() && $("#txtCodUsuario").val() == "0") {
            var li = document.createElement("li");
            li.innerHTML = "- Senhas inválidas";
            $("#ulErros").append(li);
            erros++;
        }

        if (!ValidarData(document.getElementById("txtData").value)) {
            var li = document.createElement("li");
            li.innerHTML = "- Informe uma data válida válida";
            ulErros.appendChild(li);
            erros++;
        }

        var sexo = document.getElementById("slSexo").value;
        if (sexo != "m" && sexo != "f") {
            var li = document.createElement("li");
            li.innerHTML = "- Sexo inválido";
            ulErros.appendChild(li);
            erros++;
        }

        var permissao = document.getElementById("slPermissao").value;
        if (permissao != "1" && permissao != "2") {
            var li = document.createElement("li");
            li.innerHTML = "- Permissão inválida";
            ulErros.appendChild(li);
            erros++;
        }

        if (erros === 0) {
            return true;
        } else {
            return false;
        }
    }
</script>
