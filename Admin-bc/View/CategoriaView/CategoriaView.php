<?php
require_once("../Util/UploadFile.php");
require_once("../Model/Categoria.php");
require_once("../Controller/CategoriaController.php");
$categoriaController = new CategoriaController();

$cod = "";
$nome = "";
$link = "";
$thumb = "";
$subcategoria = 0;
$descricao = "";
$resultado = "";

if (filter_input(INPUT_POST, "btnGravar", FILTER_SANITIZE_STRING)) {
    $categoria = new Categoria();
    $categoria->setCod(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));
    $categoria->setNome(filter_input(INPUT_POST, "txtNome", FILTER_SANITIZE_STRING));
    $categoria->setLink(filter_input(INPUT_POST, "txtLink", FILTER_SANITIZE_STRING));
    $categoria->setDescricao(filter_input(INPUT_POST, "txtDescricao", FILTER_SANITIZE_STRING));

    if (filter_input(INPUT_POST, "slSubcategoria", FILTER_SANITIZE_NUMBER_INT)) {
        $categoria->setSubcategoria(filter_input(INPUT_POST, "slSubcategoria", FILTER_SANITIZE_NUMBER_INT));
    } else {
        $categoria->setSubcategoria(null);
    }


    if (!filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT)) {
        //Cadastro
        $upload = new Upload();
        $nomeImagem = $upload->LoadFile("../img/Categorias/", "img", $_FILES["flImagem"]);
        $categoria->setThumb($nomeImagem);

        if ($nomeImagem != "" && $nomeImagem != "invalid") {
            //Método de cadastro

            if ($categoriaController->Cadastrar($categoria)) {
                ?>
                <script>
                    document.cookie = "msg=1";
                    document.location.href = "?pagina=categoria";
                </script>
                <?php
            } else {
                $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar cadastrar a categoria.</div>";
            }
        } else if ($nomeImagem == "invalid") {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Formato de imagem inválido.</div>";
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar carregar a imagem.</div>";
        }
    } else {
        //Editar
        if ($categoriaController->Alterar($categoria)) {
            ?>
            <script>
                document.cookie = "msg=2";
                document.location.href = "?pagina=categoria";
            </script>
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar alterar a categoria.</div>";
        }
    }
}

if (filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT)) {
    $categoria = $categoriaController->RetornarCod(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));
    if ($categoria != null) {
        $cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
        $nome = $categoria->getNome();
        $link = $categoria->getLink();
        $thumb = "img";
        $subcategoria = $categoria->getSubcategoria();
        $descricao = $categoria->getDescricao();
    }
}


$listaResumida = $categoriaController->RetornarCategoriasResumido();
$listaCategoria = $categoriaController->RetornarTodos();
?>
<div id="dvCategoriaView">
    <h1>Gerenciar Categorias</h1>
    <br />

    <div class="panel panel-default maxPanelWidth">
        <div class="panel-heading">Cadastrar e editar</div>
        <div class="panel-body">
            <form method="post" id="frmGerenciarCategoria" name="frmGerenciarCategoria" novalidate enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <input type="hidden" id="txtCodCategoria" value="<?= $cod; ?>" />
                            <label for="txtNome">Nome completo</label>
                            <input type="text" class="form-control" id="txtNome" name="txtNome" placeholder="Nome completo" value="<?= $nome; ?>">
                        </div>
                    </div>

                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="txtLink">Link</label>
                            <input type="text" class="form-control" id="txtLink" name="txtLink" placeholder="eletronicos"  value="<?= $link; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="flImagem">Selecione uma imagem</label>
                            <input type="file" id="flImagem" name="flImagem" <?= ($thumb != "" ? "disabled='disabled'" : "") ?> accept="image/*" />
                        </div>
                    </div>

                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="slSubcategoria">Subcategoria</label>
                            <select class="form-control" id="slSubcategoria" name="slSubcategoria">
                                <option value="">Selecione</option>
                                <?php
                                foreach ($listaResumida as $cat) {
                                    ?>
                                    <option value="<?= $cat->getCod() ?>" <?= ($subcategoria == $cat->getCod() ? "selected='selected'" : "") ?> <?= ($cat->getSubcategoria() == null ? "style='font-weight: bold;'" : "") ?>><?= $cat->getNome() ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="txtDescricao">Descrição</label>
                            <textarea class="form-control" rows="3" id="txtDescricao" name="txtDescricao"><?= $descricao; ?></textarea>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <p id="pResultado"><?= $resultado; ?></p>
                    </div>
                </div>
                <input class="btn btn-success" type="submit" name="btnGravar" value="Gravar">
                <a href="?pagina=categoria" class="btn btn-danger">Cancelar</a>

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
    <div class="panel panel-default maxPanelWidth">
        <div class="panel-heading">Consultar</div>
        <div class="panel-body">
            <?php
            foreach ($listaCategoria as $categoria) {
                ?>
                <div class="row">
                    <div class="col-lg-4 col-xs-12">
                        <img src="../img/Categorias/<?= $categoria->getThumb(); ?>" alt="<?= $categoria->getNome(); ?>" title="<?= $categoria->getNome(); ?>"  class="img-responsive img-thumbnail" />
                        <br /> <br />
                        <a href="?pagina=categoria&cod=<?= $categoria->getCod(); ?>" class="btn btn-warning">Editar</a>
                        <a href="?pagina=categoriaimagem&cod=<?= $categoria->getCod(); ?>&img=<?= $categoria->getThumb(); ?>" class="btn btn-info">Alterar imagem</a>
                        <br /> <br />
                    </div>
                    <div class="col-lg-6 col-xs-12">
                        <p><span class="bold">Nome:</span> <?= $categoria->getNome(); ?></p>
                        <p><span class="bold">Link:</span> <?= $categoria->getLink(); ?></p>
                        <p><span class="bold">Descrição:</span> <?= $categoria->getDescricao(); ?></p>
                    </div>
                    <div class="clear borderBottom"></div>
                    <br />
                </div>
                <br />
            <?php } ?>
        </div>
    </div>
</div>
<script src="../ckeditor/ckeditor.js"></script>
<script>
                $(document).ready(function () {
                    CKEDITOR.replace('txtDescricao');
                    if (getCookie("msg") == 1) {
                        document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Categoria cadastrada com sucesso.</div>";
                        document.cookie = "msg=d";
                    } else if (getCookie("msg") == 2) {
                        document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Categoria alterada com sucesso.</div>";
                        document.cookie = "msg=d";
                    }


                    $("#frmGerenciarCategoria").submit(function (e) {
                        if (!ValidarFormulario()) {
                            e.preventDefault();
                        }
                    });

                    function ValidarFormulario() {
                        var erros = 0;
                        var ulErros = document.getElementById("ulErros");
                        ulErros.style.color = "red";
                        ulErros.innerHTML = "";


                        //Javascript nativo
                        if (document.getElementById("txtNome").value.length < 2) {
                            var li = document.createElement("li");
                            li.innerHTML = "- Informe um nome válido";
                            ulErros.appendChild(li);
                            erros++;
                        }

                        if (document.getElementById("txtLink").value.length < 2) {
                            var li = document.createElement("li");
                            li.innerHTML = "- Informe um link válido";
                            ulErros.appendChild(li);
                            erros++;
                        }

                        if ($("#txtCodCategoria").val() == "") {
                            if (document.getElementById("flImagem").value == "") {
                                var li = document.createElement("li");
                                li.innerHTML = "- Selecione uma imagem";
                                ulErros.appendChild(li);
                                erros++;
                            }
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