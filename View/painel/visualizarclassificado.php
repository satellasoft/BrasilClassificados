<?php
require_once("Controller/ClassificadoController.php");
require_once("Controller/ImagemController.php");

require_once("Controller/ComentarioController.php");
require_once("Model/Comentario.php");

require_once("Model/Classificado.php");
$cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
$del = filter_input(INPUT_GET, "del", FILTER_SANITIZE_NUMBER_INT);

$comentarioController = new ComentarioController();

$classificadoController = new ClassificadoController();
$classificado = $classificadoController->RetornarAnuncioClassificadoCod($cod);

if ($del) {
    if ($comentarioController->Remover($del)) {
        ?>
        <script>
            setCookie("result", "1", 1);
            document.location.href = "?pagina=visualizarclassificado&cod=<?= $cod ?>";
        </script>
        <?php
    } else {
        ?>
        <script>
            setCookie("result", "-1", 1);
            document.location.href = "?pagina=visualizarclassificado&cod=<?= $cod ?>";
        </script>
        <?php
    }
}

if (filter_input(INPUT_POST, "btnResponder", FILTER_SANITIZE_STRING)) {
    $comentario = new Comentario();

    $comentario->setMensagem(filter_input(INPUT_POST, "txtComentarioAnunciante", FILTER_SANITIZE_SPECIAL_CHARS));
    $comentario->setSubComentario(filter_input(INPUT_POST, "txtCod", FILTER_SANITIZE_NUMBER_INT));
    $comentario->getClassificado()->setCod($cod);
    $comentario->getUsuario()->setCod($_SESSION["cod"]);

    if ($comentarioController->Cadastrar($comentario)) {
        ?>
        <script>
            setCookie("result", "2", 1);
            document.location.href = "?pagina=visualizarclassificado&cod=<?= $cod ?>";
        </script>
        <?php
    } else {
        ?>
        <script>
            setCookie("result", "-2", 1);
            document.location.href = "?pagina=visualizarclassificado&cod=<?= $cod ?>";
        </script>
        <?php
    }
}
?>
<div id="dvVisualizarAnuncio">
    <?php
    if ($classificado->getNome() != null && isset($_SESSION['cod'])) {

        $imagemController = new ImagemController();

        $listaImagem = $imagemController->RetornarImagensClassificado($cod, $_SESSION["cod"]);


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
        <?= html_entity_decode($classificado->getDescricao()); ?>
        <br />   <br />
        <p><span class="bold"> Tipo de negócio:</span> <?= $tipoAnucio; ?></p>
        <p><span class="bold"> Valor:</span> R$ <?= number_format($classificado->getValor(), 2, ",", "."); ?></p>
        <p><span class="bold"> Categoria:</span> <?= $classificado->getCategoria()->getNome(); ?></p>

        <br />
        <p><span class="bold"> Vendedor:</span> <?= $classificado->getUsuario()->getNome(); ?></p>
        <p><span class="bold"> Vendedor e-mail:</span> <?= $classificado->getUsuario()->getEmail(); ?></p>
        <br />
        <div id="dvImagensAnuncioPainel">
            <?php
            foreach ($listaImagem as $img) {
                ?>
                <a href="#dvImagensAnuncioPainel" onclick="OpenModal('<?= $img[0]; ?>');"><img src="img/Classificados/<?= $img[0]; ?>" alt="<?= $classificado->getNome(); ?>"></a>
                <?php
            }
            ?>
        </div>
        <br>
        <!--Comentários-->
        <div id="dvRespondercomentario" style="display: none;">
            <p><span class="bold">Responder para: </span> <span id="spNomeUsuario"></span></p>
            <br>
            <form method="post" id="frmResponder">
                <span>Mensagem: </span><br><br>
                <input type="hidden" id="txtCod" name="txtCod" />
                <textarea id="txtComentarioAnunciante" name="txtComentarioAnunciante"></textarea>
                <br>      
                <input type="submit" name="btnResponder" id="btnResponder" value="Responder" class="btn-padrao" />
            </form>
            <div class="clear"></div>
        </div>
        <br>
        <div id="dvComentariosUsuarios">
            <?php
            $comentarioController = new ComentarioController();
            $listaComentario = $comentarioController->RetornarTodosClassificadoCod($cod);

            if (!empty($listaComentario)) {
                $listaComentarioPricipal = [];
                $listaComentarioSecundario = [];

                foreach ($listaComentario as $cm) {
                    if (empty($cm->getSubComentario())) {
                        $listaComentarioPricipal[] = $cm;
                    } else {
                        $listaComentarioSecundario[] = $cm;
                    }
                }
                foreach ($listaComentarioPricipal as $comentario) {
                    ?>
                    <div class="dvComment">
                        <p><span class="bold">Publicado em: </span> <?= date("d/m/Y H:i", strtotime($comentario->getData())) ?> | <span class="bold">Por: </span><?= $comentario->getUsuario()->getNome(); ?> | <span class="bold"> Email: </span><?= $comentario->getUsuario()->getEmail(); ?></p>
                        <p style="margin-top: 5px;"><?= $comentario->getMensagem(); ?></p>
                        <br>
                        <?php
                        if ($comentario->getStatus() != 2) {
                            $mostraComentario = true;
                            foreach ($listaComentarioSecundario as $comentarioSecundario) {
                                if ($comentarioSecundario->getSubcomentario() == $comentario->getCod()) {
                                    $mostraComentario = false;
                                }
                            }
                            if ($mostraComentario || $listaComentarioSecundario == null) {
                                ?>
                                <a href="#dvRespondercomentario" class="btnacao blue" onclick="ResponderComentario('<?= $comentario->getUsuario()->getNome(); ?>', <?= $comentario->getCod(); ?>)"><img src="img/icones/comentario.png" alt="Imagem Responder"/>Responder</a>
                                <?php
                            }
                            ?>
                            <a href="?pagina=visualizarclassificado&cod=<?= $cod ?>&del=<?= $comentario->getCod(); ?>" class="btnacao red"><img src="img/icones/remover.png" alt="Imagem Remover"/>Remover</a>
                            <?php
                        } else {
                            echo "Comentário removido";
                        }
                        ?>
                        <?php
                        foreach ($listaComentarioSecundario as $comentarioSecundario) {

                            if ($comentarioSecundario->getSubcomentario() == $comentario->getCod()) {
                                ?>
                                <br> <br>
                                <div class="comentarioAnunciante">
                                    <p class="bold">Resposta do anunciante em: <?= date("d/m/Y H:i", strtotime($comentarioSecundario->getData())) ?></p>
                                    <br>
                                    <?= html_entity_decode($comentarioSecundario->getMensagem()); ?>
                                </div>
                                <?php
                            }
                            ?>

                            <?php
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
        <h1>Anúncio não encontrado</h1>
        <br />
        <p>Desculpe, o anúncio que você procura não foi encontrado em nossa base de dados.</p>
        <?php
    }
    ?>
</div>
<div id="dvModal">
    <div>
        <img src="" alt="Imagem Classificado" id="imgModal" />
        <br>
        <button onclick="CloseModal();">Fechar</button>
    </div>
</div>

<script src="ckeditor/ckeditor.js"></script>
<script>
            $(document).ready(function () {
                CKEDITOR.replace('txtComentarioAnunciante');

                var result = getCookie("result");

                if (result == "1") {
                    alert("Comentário removido.");
                    DeleteCookie("result");
                } else if (result == "-1") {
                    alert("Houve um erro ao tentar comentar.");
                    DeleteCookie("result");
                }

                if (result == "2") {
                    alert("Comentário respondido.");
                    DeleteCookie("result");
                } else if (result == "-2") {
                    alert("Houve um erro ao tentar responder o comentário.");
                    DeleteCookie("result");
                }


                $("#frmResponder").submit(function (event) {
                    var value = CKEDITOR.instances['txtComentarioAnunciante'].getData();
                    if (value.length < 5) {
                        alert("Informe ao menos 5 caracteres.");
                        event.preventDefault();
                    }
                });

            });

            function OpenModal(image) {
                $("#dvModal").show("normal");
                document.getElementById("imgModal").src = "img/classificados/" + image;
            }
            function CloseModal() {
                $("#dvModal").hide("normal");
            }

            function ResponderComentario(nomeUsuario, comentarioCod) {
                document.getElementById("txtCod").value = comentarioCod;
                document.getElementById("spNomeUsuario").innerHTML = nomeUsuario;

                document.getElementById("dvRespondercomentario").style.display = "inline";
            }


</script>