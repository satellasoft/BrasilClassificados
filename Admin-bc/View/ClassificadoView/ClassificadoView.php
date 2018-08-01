<?php
require_once("../Model/Categoria.php");
require_once("../Model/Classificado.php");
require_once("../Controller/CategoriaController.php");
require_once("../Controller/ClassificadoController.php");
$categoriaController = new CategoriaController();
$classificadoController = new ClassificadoController();

$ctg = 0;
$nome = "";
$descricao = "";
$status = 1;
$perfil = 2;
$tipo = 1;
$valor = "";

$resultado = "";


if (filter_input(INPUT_POST, "btnGravar", FILTER_SANITIZE_STRING)) {
    $classificado = new Classificado();

    $classificado->setCod(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));
    $classificado->setNome(filter_input(INPUT_POST, "txtNome", FILTER_SANITIZE_STRING));
    $classificado->setDescricao(filter_input(INPUT_POST, "txtDescricao", FILTER_SANITIZE_STRING));
    $classificado->setValor(filter_input(INPUT_POST, "txtValor", FILTER_SANITIZE_STRING));
    $classificado->setPerfil(filter_input(INPUT_POST, "slPerfil", FILTER_SANITIZE_NUMBER_INT));
    $classificado->setStatus(filter_input(INPUT_POST, "slStatus", FILTER_SANITIZE_NUMBER_INT));
    $classificado->setTipo(filter_input(INPUT_POST, "slTipo", FILTER_SANITIZE_NUMBER_INT));
    $classificado->getCategoria()->setCod(filter_input(INPUT_POST, "slCategoria", FILTER_SANITIZE_NUMBER_INT));
    $classificado->getUsuario()->setCod($_SESSION["cod"]);

    if (!filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT)) {
        //Cadastrar
        if ($classificadoController->Cadastrar($classificado)) {
            ?>
            <script>
                document.cookie = "msg=1";
                document.location.href = "?pagina=classificado";
            </script>
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar cadastrar o classificado.</div>";
        }
    } else {
        //Editar
        if ($classificadoController->Alterar($classificado)) {
            ?>
            <script>
                document.cookie = "msg=2";
                document.location.href = "?pagina=classificado";
            </script>
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar alterar o classificado.</div>";
        }
    }
}

$listaBusca = [];

if (filter_input(INPUT_POST, "btnBuscar", FILTER_SANITIZE_STRING)) {
    $status = filter_input(INPUT_POST, "slStatusBusca", FILTER_SANITIZE_NUMBER_INT);
    $tipo = filter_input(INPUT_POST, "slTipoBusca", FILTER_SANITIZE_NUMBER_INT);
    $categoriaCod = filter_input(INPUT_POST, "slCategoriaBusca", FILTER_SANITIZE_NUMBER_INT);
    $perfil = filter_input(INPUT_POST, "slPerfilBusca", FILTER_SANITIZE_NUMBER_INT);
    $termo = filter_input(INPUT_POST, "txtTermo", FILTER_SANITIZE_STRING);

    $listaBusca = $classificadoController->RetornarTodosFiltro($termo, $tipo, $status, $perfil, $categoriaCod);
}

if (filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT)) {
    $classificado = $classificadoController->RetornarCod(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));

    $ctg = $classificado->getCategoria()->getCod();
    $nome = $classificado->getNome();
    $descricao = $classificado->getDescricao();
    $status = $classificado->getStatus();
    $perfil = $classificado->getPerfil();
    $tipo = $classificado->getTipo();
    $valor = number_format($classificado->getValor(), 2, ",", ".");
    echo $valor;
}

$listaResumida = $categoriaController->RetornarCategoriasResumido();
?>
<div id="dvClassificadoView">
    <h1>Gerenciar Classificados</h1>
    <br />

    <div class="panel panel-default maxPanelWidth">
        <div class="panel-heading">Cadastrar e editar</div>
        <div class="panel-body">
            <form method="post" id="frmGerenciarClassificado" name="frmGerenciarClassificado" novalidate>
                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="txtNome">Nome do produto</label>
                            <input type="text" class="form-control" id="txtNome" name="txtNome" placeholder="Nome " value="<?= $nome; ?>">
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-12">
                        <div class="form-group">
                            <label for="slTipo">Tipo de anúncio</label>
                            <select class="form-control" id="slTipo" name="slTipo">
                                <option value="1" <?= ($tipo == 1 ? "selected='selected'" : ""); ?>>Venda</option>
                                <option value="2" <?= ($tipo == 2 ? "selected='selected'" : ""); ?> >Troca</option>
                                <option value="3" <?= ($tipo == 3 ? "selected='selected'" : ""); ?>>Doação</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-12">
                        <div class="form-group">
                            <label for="slStatus">Status</label>
                            <select class="form-control" id="slStatus" name="slStatus">
                                <option value="1" <?= ($status == 1 ? "selected='selected'" : ""); ?>>Ativo</option>
                                <option value="2" <?= ($status == 2 ? "selected='selected'" : ""); ?>>Bloqueado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="txtValor">Valor</label>
                            <input type="text" class="form-control" id="txtValor" name="txtValor" placeholder="" value="<?= $valor; ?>">
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-12">
                        <div class="form-group">
                            <label for="slPerfil">Perfil de anúncio</label>
                            <select class="form-control" id="slPerfil" name="slPerfil">
                                <option value="1" <?= ($perfil == 1 ? "selected='selected'" : ""); ?>>Patrocinado</option>
                                <option value="2" <?= ($perfil == 2 ? "selected='selected'" : ""); ?>>Comum</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-12">
                        <div class="form-group">
                            <label for="slCategoria">Categoria</label>
                            <select class="form-control" id="slCategoria" name="slCategoria">
                                <option value="">Selecione</option>
                                <?php
                                foreach ($listaResumida as $cat) {
                                    ?>
                                    <option value="<?= $cat->getCod() ?>" <?= ($ctg == $cat->getCod() ? "selected='selected'" : "") ?> <?= ($cat->getSubcategoria() == null ? "style='font-weight: bold;'" : "") ?>><?= $cat->getNome() ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <p style="font-weight: 700;">Descrição</p>
                        <textarea class="form-control" id="txtDescricao" name="txtDescricao"><?= $descricao; ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <p id="pResultado"><?= $resultado; ?></p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <input class="btn btn-success" type="submit" name="btnGravar" value="Gravar">
                    <a href="?pagina=classificado" class="btn btn-danger">Cancelar</a>
                </div>
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
    <!--BUSCA-->
    <div class="panel panel-default maxPanelWidth" id="dvBusca">
        <div class="panel-heading">Consultar</div>
        <div class="panel-body">
            <form method="post" name="frmBuscar" id="frmBuscar" action="#dvBusca">
                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="txtTermo">Termo de busca</label>
                            <input type="text" class="form-control" id="txtTermo" name="txtTermo" placeholder="Termo" value="">
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-12">
                        <div class="form-group">
                            <label for="slTipoBusca">Tipo de anúncio</label>
                            <select class="form-control" id="slTipoBusca" name="slTipoBusca">
                                <option value="1">Venda</option>
                                <option value="2">Troca</option>
                                <option value="3">Doação</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-12">
                        <div class="form-group">
                            <label for="slStatusBusca">Status</label>
                            <select class="form-control" id="slStatusBusca" name="slStatusBusca">
                                <option value="1">Ativo</option>
                                <option value="2">Bloqueado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="slPerfilBusca">Perfil de anúncio</label>
                            <select class="form-control" id="slPerfilBusca" name="slPerfilBusca">
                                <option value="1">Patrocinado</option>
                                <option value="2">Comum</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="slCategoriaBusca">Categoria</label>
                            <select class="form-control" id="slCategoriaBusca" name="slCategoriaBusca">
                                <option value="">Selecione</option>
                                <?php
                                foreach ($listaResumida as $cat) {
                                    ?>
                                    <option value="<?= $cat->getCod() ?>" <?= ($ctg == $cat->getCod() ? "selected='selected'" : "") ?> <?= ($cat->getSubcategoria() == null ? "style='font-weight: bold;'" : "") ?>><?= $cat->getNome() ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <br />
                            <input class="btn btn-success" type="submit" name="btnBuscar" value="Buscar">
                        </div>
                    </div>
                </div>
            </form>
            <br />
            <hr />
            <br />

            <table class="table table-hover table-responsive table-striped">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($listaBusca != null) {
                        foreach ($listaBusca as $classificado) {
                            ?>
                            <tr>
                                <td>#<?= $classificado->getCod(); ?></td>
                                <td><?= $classificado->getNome(); ?></td>
                                <td><?= ($classificado->getStatus() == 1 ? "<span class='glyphicon glyphicon-ok' style='color: green;'></span> <span style='color: green;'>Ativo</span>" : "<span class='glyphicon glyphicon-remove' style='color: red;'></span> <span style='color: red;'>Bloqueado</span>"); ?></td>
                                <td>
                                    <a href="?pagina=visualizarclassificado&cod=<?= $classificado->getCod(); ?>" class="btn btn-success">Visualizar</a>
                                    <a href="?pagina=classificado&cod=<?= $classificado->getCod(); ?>" class="btn btn-warning">Editar</a>
                                    <a href="?pagina=gerenciarimagemclassificado&cod=<?= $classificado->getCod(); ?>" class="btn btn-info">Gerenciar imagens</a>
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
<script src="../ckeditor/ckeditor.js"></script>
<script>
    $(document).ready(function () {
        CKEDITOR.replace('txtDescricao');
        $('#txtValor').mask('000.000.000.000.000,00', {reverse: true});

        if (getCookie("msg") == 1) {
            document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Classificado cadastrado com sucesso.</div>";
            document.cookie = "msg=d";
        } else if (getCookie("msg") == 2) {
            document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Classificado alterado com sucesso.</div>";
            document.cookie = "msg=d";
        }

        $("#frmGerenciarClassificado").submit(function (e) {
            if (!ValidarFormulario()) {
                e.preventDefault();
            }
        });

        $("#frmBuscar").submit(function (e) {
            if ($("#txtTermo").val() == "" || $("#slCategoriaBusca").val() == "") {
                alert("Formulário de busca inválido!!!");
                e.preventDefault();
            }
        });



        function ValidarFormulario() {
            var erros = 0;
            var ulErros = document.getElementById("ulErros");
            ulErros.style.color = "red";
            ulErros.innerHTML = "";

            if (document.getElementById("txtNome").value.length <= 0) {
                var li = document.createElement("li");
                li.innerHTML = "- Informe um nome válido";
                ulErros.appendChild(li);
                erros++;
            }

            if (document.getElementById("slTipo").value < 0) {
                var li = document.createElement("li");
                li.innerHTML = "- Informe um tipo de anúncio";
                ulErros.appendChild(li);
                erros++;
            }

            if (document.getElementById("slStatus").value < 0) {
                var li = document.createElement("li");
                li.innerHTML = "- Informe um status";
                ulErros.appendChild(li);
                erros++;
            }

            if (document.getElementById("slPerfil").value < 0) {
                var li = document.createElement("li");
                li.innerHTML = "- Informe um perfil";
                ulErros.appendChild(li);
                erros++;
            }

            if (document.getElementById("slPerfil").value == "") {
                var li = document.createElement("li");
                li.innerHTML = "- Selecione uma categoria";
                ulErros.appendChild(li);
                erros++;
            }

            if (document.getElementById("slCategoria").value == "") {
                var li = document.createElement("li");
                li.innerHTML = "- Selecione uma categoria";
                ulErros.appendChild(li);
                erros++;
            }

            var value = CKEDITOR.instances['txtDescricao'].getData();
            if (value.length < 10) {
                var li = document.createElement("li");
                li.innerHTML = "- Informe uma descrição";
                ulErros.appendChild(li);
                erros++;
            }

            if (erros === 0) {
                return true;
            } else {
                return false;
            }
        }
    });
</script>