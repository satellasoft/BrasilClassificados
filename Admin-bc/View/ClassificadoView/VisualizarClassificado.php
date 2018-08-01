<?php
require_once("../Model/Categoria.php");
require_once("../Model/Classificado.php");
require_once("../Model/Imagem.php");
require_once("../Controller/ClassificadoController.php");
require_once("../Controller/ImagemController.php");

$classificadoController = new ClassificadoController();
$imagemController = new ImagemController();

$cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
$classificado = $classificadoController->RetornarCompletoCod($cod);
$imagens = $imagemController->CarregarImagensClassificado($cod);

$tipoAnucio = "";
if ($classificado->getTipo() == 1) {
    $tipoAnucio = "Venda";
} else if ($classificado->getTipo() == 2) {
    $tipoAnucio = "Troca";
} else {
    $tipoAnucio = "Doação";
}
?>
<div id="dvImagensClassificadoView">
    <h1>Visualizar classificado</h1>
    <br />
    <?php
    if ($classificado->getNome() != null) {
        ?>
        <div class = "panel panel-info maxPanelWidth">
            <div class = "panel-heading bold"><?= $classificado->getNome(); ?></div>
            <div class = "panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php
                        if ($imagens != null) {
                            ?>
                            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                <!-- Indicators -->
                                <ol class="carousel-indicators">
                                    <?php
                                    for ($i = 0; $i < count($imagens); $i++) {
                                        ?>
                                        <li data-target="#myCarousel" data-slide-to="<?= $i; ?>" <?= ($i == 0 ? "class='active'" : ""); ?></li>
                                        <?php
                                    }
                                    ?>
                                </ol>

                                <!-- Wrapper for slides -->
                                <div class="carousel-inner" role="listbox">

                                    <?php
                                    $cont = 0;
                                    foreach ($imagens as $ima) {
                                        ?>
                                        <div <?= ($cont == 0 ? "class=\"item active\"" : "class=\"item\"") ?>>
                                            <img src = "../img/Classificados/<?= $ima->getImagem(); ?>" alt = "<?= $classificado->getNome(); ?>">
                                        </div>
                                        <?php
                                        $cont++;
                                    }
                                    ?>

                                </div>

                                <!-- Left and right controls -->
                                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-xs-12">
                        <p><span class="bold">Categoria:</span> <?= $classificado->getCategoria()->getNome(); ?></p>
                        <p><span class="bold">Usuário:</span> <?= $classificado->getUsuario()->getNome(); ?></p>
                        <p><span class="bold">Status:</span> <?= ($classificado->getStatus() == 1 ? "<span style='color: green;'>Ativo</span>" : "<span style='color: red;'>Bloqueado</span>"); ?></p>
                        <p><span class="bold">Perfil de anúncio:</span> <?= ($classificado->getPerfil() == 1 ? "<span style='color: green;'>Patrocinado</span>" : "<span style='color: blue;'>Comum</span>"); ?></p>
                        <p><span class="bold">Tipo de anúncio:</span> <?= $tipoAnucio; ?></p>
                        <p><span class="bold">Valor:</span> <?= number_format($classificado->getValor()); ?></p>
                        <p><span class="bold">Descrição:</span></p>
                        <?= $classificado->getDescricao(); ?>
                        <br /> <br />
                        <a href="?pagina=classificado&cod=<?= $cod; ?>" class="btn btn-warning">Editar</a>
                        <a href="?pagina=gerenciarimagemclassificado&cod=<?= $cod; ?>" class="btn btn-info">Gerenciar imagens</a>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class = "panel panel-default maxPanelWidth">
                <div class="panel-heading">Nada encontrado</div>
                <div class="panel-body">
                    <p>Desculpe, nenhuma informação foi encontrada.</p>
                </div>
            </div>
            <?php
        }
        ?>

    </div>