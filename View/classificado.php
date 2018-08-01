<?php
$cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
$classificado = null;
$resultado = " ";

if ($cod > 0) {

    require_once("Controller/ClassificadoController.php");
    require_once("Controller/ImagemController.php");
    require_once("Model/Classificado.php");

    require_once("Controller/ComentarioController.php");
    require_once("Model/Comentario.php");

    $classificadoController = new ClassificadoController();
    $imagemController = new ImagemController();
    $comentarioController = new ComentarioController();

    $classificado = $classificadoController->RetornarAnuncioClassificadoCod($cod);

    if (filter_input(INPUT_POST, "btnComentar", FILTER_SANITIZE_STRING)) {

        $comentario = new Comentario();
        $comentario->getUsuario()->setCod($_SESSION["cod"]);
        $comentario->getClassificado()->setCod(filter_input(INPUT_POST, "txtCod", FILTER_SANITIZE_NUMBER_INT));
        $comentario->setMensagem(strip_tags(filter_input(INPUT_POST, "txtComentario", FILTER_SANITIZE_STRING)));

        if ($comentarioController->Cadastrar($comentario)) {
            $resultado = "<span class='spSucesso'>Comentário enviado!</span>";
        } else {
            $resultado = "<span class='spErro'>Houve um erro ao tentar enviar o comentário!</span>";
        }
    }
}
?>

<div id="dvClassificado">
    <?php
    if ($classificado->getCod() > 0) {
        $listaImagem = $imagemController->RetornarImagensClassificadoResumida($cod);

        $tipoAnucio = "";
        if ($classificado->getTipo() == 1) {
            $tipoAnucio = "Venda";
        } else if ($classificado->getTipo() == 2) {
            $tipoAnucio = "Troca";
        } else {
            $tipoAnucio = "Doação";
        }
        ?>
        <h1><?= $classificado->getNome(); ?></h1>
        <br />
        <!--        <div id="sliderImagemClassificado">
                    <img src="img/Classificados/<?= $listaImagem[0][0]; ?>" alt=""/>
                </div>-->
        <div id="dvImagensAnuncioPainel">
            <?php
            foreach ($listaImagem as $img) {
                ?>
                <a href="#" onclick="OpenModal('<?= $img; ?>');"><img src="img/Classificados/<?= $img; ?>" alt="Imagem - <?= $classificado->getNome(); ?>"></a>
                <?php
            }
            ?>
        </div>
        <br />
        <p><span class="bold">Proprietário:</span> <?= $classificado->getUsuario()->getNome(); ?></p>
        <p><span class="bold">E-mail:</span> <?= $classificado->getUsuario()->getEmail(); ?></p> <!--Verificar se é necessário o envio de e-mail-->
        <div class="line"></div>

        <p><span class="bold">Categoria:</span> <?= $classificado->getCategoria()->getNome(); ?></p>
        <br/>
        <p><span class="bold">Tipo de anúncio:</span> <?= $tipoAnucio; ?></p>
        <br/>
        <p><span class="bold">Valor:</span> R$ <?= number_format($classificado->getValor(), 2, ",", " "); ?></p>
        <br/>
        <p><span class="bold">Descrição:</span> <?= html_entity_decode($classificado->getDescricao()); ?></p>
        <br />

        <?php
        if (isset($_SESSION["cod"])) {
            ?>
            <div id="dvComentario" class="formcontroles">
                <form method="post" name="frmComentario" id="frmComentario" action="#dvComentario">
                    <!--1º row-->
                    <div class="linha">
                        <div class="grid-100 coluna">
                            <label for="txtNome">Comentário - <span id="spCaracteresRestantes" class="bold">500</span> caracteres restantes.</label>
                            <textarea id="txtComentario" name="txtComentario" style='outline: none;'></textarea>
                            <input type="hidden" id="txtCod" name="txtCod" value="<?= $classificado->getCod(); ?>" />
                        </div>
                    </div>

                    <div class="clear"></div>

                    <div class="linha">
                        <div class="grid-100 coluna">
                            <p id="pResultado"><?= $resultado; ?></p>
                        </div>
                    </div>

                    <div class="clear"></div>

                    <div class="linha">
                        <div class="grid-100 coluna">
                            <input type="submit" name="btnComentar" id="btnComentar" value="Comentar" class="btn-padrao" />
                        </div>
                    </div>
                </form>
                <br>
                <!--Os últimos 10 comentários-->
            </div>
            <?php
        } else {
            echo "Para comentar você precisa estar autenticado.";
        }
        ?>
        <br>  <br>
        <div id="dvComentariosUsuarios">
            <?php
            $comentarioController = new ComentarioController();
            $listaComentario = $comentarioController->RetornarUltmosClassificado($cod);

            if (!empty($listaComentario)) {
                $comentarioPrincipal = array();
                $comentarioSecundario = array();

                foreach ($listaComentario as $comentario) {
                    if ($comentario->getSubComentario() == null) {
                        $comentarioPrincipal[] = $comentario;
                    } else {
                        $comentarioSecundario[] = $comentario;
                    }
                }

                foreach ($comentarioPrincipal as $comentario) {
                    ?>
                    <div class="dvComment">
                        <p><span class="bold">Publicado em: </span> <?= date("d/m/Y H:i", strtotime($comentario->getData())) ?>| <span class="bold">Por: </span><?= $comentario->getUsuario()->getNome(); ?></p>
                        <p style="margin-top: 5px;"><?= html_entity_decode($comentario->getMensagem()); ?></p>
                        <br>

                        <?php
                        foreach ($comentarioSecundario as $subcomentario) {
                            if ($subcomentario->getSubComentario() == $comentario->getCod()) {
                                ?>
                                <p><span class="bold">Resposta do anunciante em:</span> <?= date("d/m/Y H:i:s", strtotime($subcomentario->getData())); ?></p>
                                <div style="color: #0288D1">
                                    <?= html_entity_decode($subcomentario->getMensagem()); ?>    
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <br>
                    <?php
                }
            } else {
                echo "Nenhum comentário a ser exibido!";
            }
            ?>
        </div>
        <?php
    } else {
        ?>
        <h1>Conteúdo não encontrado</h1>
        <br />
        <div>
            <p>Desculpe, o classificado que você procura não existe ou não foi encontrado.</p>
            <p>Por favor, faça uma busca através do menu acima.</p>
        </div>
        <?php
    }
    ?>

</div>
<br />
<div id="dvModal">
    <div>
        <img src="" alt="Imagem Classificado" id="imgModal" />
        <br>
        <button onclick="CloseModal();">Fechar</button>
    </div>
</div>
<script>
    function OpenModal(image) {
        $("#dvModal").show("normal");
        document.getElementById("imgModal").src = "img/classificados/" + image;
    }
    function CloseModal() {
        $("#dvModal").hide("normal");
    }

    $(document).ready(function () {
        $("#txtComentario").keyup(function () {
            ContarCaracteres();
        });


        $("#frmComentario").submit(function (event) {

            if (ContarCaracteres() < 0) {
                event.preventDefault();
                $("#pResultado").text("Formulário inválido");
                $("#pResultado").css("color", "red");
            }
        });


    });

    function ContarCaracteres() {
        var total = 500;
        var txtComentario = $("#txtComentario").val();

        var atual = (total - txtComentario.length);

        if (atual < 0) {
            $("#spCaracteresRestantes").css("color", "red");
            $("#txtComentario").css("border", "1px solid red");
        } else {
            $("#spCaracteresRestantes").css("color", "black");
            $("#txtComentario").css("border", "1px solid green");
        }

        $("#spCaracteresRestantes").text(atual);
        return atual;
    }
</script>