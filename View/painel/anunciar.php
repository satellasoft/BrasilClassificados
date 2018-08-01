<?php
require_once("Controller/CategoriaController.php");
require_once("Controller/ClassificadoController.php");
require_once("Model/Categoria.php");
require_once("Model/Classificado.php");

$categoriaController = new CategoriaController();
$classificadoController = new ClassificadoController();

$edit = false;
$nome = "";
$valor = "";
$tipo = 1;
$ctg = "";
$descricao = "";
$listaResumida = $categoriaController->RetornarCategoriasResumido();
$resultado = "";
$cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);

if (filter_input(INPUT_POST, "btnSubmit", FILTER_SANITIZE_STRING)) {
    $classificado = new Classificado();
    $classificado->setCod($cod);
    $classificado->setStatus(1);
    $classificado->setPerfil(2);
    $classificado->getCategoria()->setCod(filter_input(INPUT_POST, "slCategoria", FILTER_SANITIZE_NUMBER_INT));
    $classificado->setTipo(filter_input(INPUT_POST, "slTipo", FILTER_SANITIZE_NUMBER_INT));
    $classificado->setDescricao(filter_input(INPUT_POST, "txtDescricao", FILTER_SANITIZE_SPECIAL_CHARS));
    $classificado->setNome(filter_input(INPUT_POST, "txtNomeProduto", FILTER_SANITIZE_STRING));
    $classificado->setValor(filter_input(INPUT_POST, "txtValor", FILTER_SANITIZE_STRING));
    $classificado->getUsuario()->setCod($_SESSION["cod"]);

    if (!$cod) {
        //Cadastrar
        if ($classificadoController->Cadastrar($classificado)) {
            $resultado = "<span class='spSucesso'>Classificado cadastrado com sucesso!</span>";
        } else {
            $resultado = "<span class='spErro'>Houve um erro ao tentar cadastrar o classificado!</span>";
        }
    } else {
        //Edição
        if ($classificadoController->AlterarResumido($classificado)) {
            $resultado = "<span class='spSucesso'>Classificado alterado com sucesso!</span>";
        } else {
            $resultado = "<span class='spErro'>Houve um erro ao tentar alterar o classificado!</span>";
        }
    }
}

if ($cod) {
    $classificado = $classificadoController->RetornarCod($cod);

    if (!empty($classificado)) {
        $nome = $classificado->getNome();
        $valor = number_format($classificado->getValor(), 2, ",", ".");
        $tipo = $classificado->getTipo();
        $ctg = $classificado->getCategoria()->getCod();
        $descricao = $classificado->getDescricao();
        $edit = true;
    }
}
?>
<div id="dvAnunciar">
    <h1>Crie um anúncio grátis</h1>
    <br />

    <?php
    if (isset($_SESSION["cod"])) {
        ?>
        <div id="dvFormularioAnuncio" class="formcontroles">
            <form method="post" name="frmAnunciar" id="frmAnunciar">
                <!--2º row-->
                <div class="linha">
                    <div class="grid-50 mobile-grid-100 coluna">
                        <label for="txtNomeProduto">Nome do produto</label>
                        <input type="text" id="txtNomeProduto" name="txtNomeProduto" placeholder="Fogão de quatro bocas" value="<?= $nome; ?>" <?= ($edit == true ? "disabled='disabled'" : "") ?>/>
                    </div>

                    <div class="grid-50 mobile-grid-100 coluna">
                        <label for="slTipo">Tipo de anúncio</label>
                        <select id="slTipo" name="slTipo" <?= ($edit == true ? "disabled='true'" : "") ?>>
                            <option value="1" <?= ($tipo == 1 ? "selected='selected'" : ""); ?>>Venda</option>
                            <option value="2" <?= ($tipo == 2 ? "selected='selected'" : ""); ?> >Troca</option>
                            <option value="3" <?= ($tipo == 3 ? "selected='selected'" : ""); ?>>Doação</option>
                        </select>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="linha">
                    <div class="grid-50 mobile-grid-100 coluna">
                        <label for="slCategoria">Categoria</label>
                        <select id="slCategoria" name="slCategoria"  <?= ($edit ? "disabled='disabled'" : "") ?>>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($listaResumida as $cat) {
                                ?>
                                <option value="<?= $cat->getCod() ?>" <?= ($ctg == $cat->getCod() ? "selected='selected'" : "") ?> <?= ($cat->getSubcategoria() == null ? "style='font-weight: bold;'" : "") ?>><?= $cat->getNome() ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="grid-50 mobile-grid-100 coluna">
                        <label for="txtValor">Valor</label>
                        <input type="text" id="txtValor" name="txtValor" placeholder="" value="<?= $valor; ?>" />
                    </div>
                </div>
                <div class="clear"></div>
                <div class="linha">
                    <div class="grid-100 coluna">
                        <label for="txtDescricao">Descrição</label>
                        <textarea class="form-control" id="txtDescricao" name="txtDescricao"><?= $descricao; ?></textarea>

                    </div>
                </div>
                <div class="clear"></div>
                <div class="linha">
                    <div class="grid-100 coluna right">
                        <input type="submit" name="btnSubmit" id="btnSubmit" value="<?= (!$edit ? "Criar anúncio" : "Alterar anúncio") ?>" class="btn-padrao" />
                    </div>
                </div>
                <div class="clear"></div>

                <div class="linha">
                    <div class="grid-100 coluna">
                        <span id="spResultado"><?= $resultado; ?></span>
                    </div>
                </div>

                <div class="linha">
                    <div class="grid-100 coluna">
                        <ul id="ulErros"></ul>
                    </div>
                </div>

                <br />
            </form>
        </div>
        <?php
    } else {
        ?>
        <p>Você precisa estar autenticado para acessar este conteúdo, clique <a href="?pagina=entrar">aqui</a> fazer o login.</p>
        <br>  
        <?php
    }
    ?>
</div>
<script src="js/mask.js" type="text/javascript"></script>
<script src="ckeditor/ckeditor.js"></script>
<script>
    $(document).ready(function () {
        CKEDITOR.replace('txtDescricao');
        $('#txtValor').mask('#.##0,00', {reverse: true});

        $("#frmAnunciar").submit(function (event) {
            if (!Validar()) {
                event.preventDefault();
            }
        });
    });

    function Validar() {
        var erros = 0;
        var ulErros = document.getElementById("ulErros");
        ulErros.innerHTML = "";
        ulErros.style.color = "red";

        if ($("#txtNomeProduto").val().length < 5) {
            ulErros.innerHTML += "<li>- Informe um nome válido. (min. 5 caracteres)</li>";
            erros++;
        }

        if ($("#slCategoria").val() == "") {
            ulErros.innerHTML += "<li>- Selecione a categoria do seu produto</li>";
            erros++;
        }

        var value = CKEDITOR.instances['txtDescricao'].getData();
        if (value.length < 10) {
            ulErros.innerHTML += "<li>- Informe uma descrição</li>";
            erros++;
        }

        if (erros === 0) {
            return true;
        } else {
            return false;
        }
    }
</script>
