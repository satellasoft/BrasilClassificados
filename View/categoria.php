<?php
$listaConsulta = [];
if (filter_input(INPUT_GET, "termo", FILTER_SANITIZE_STRING) && filter_input(INPUT_GET, "cat", FILTER_SANITIZE_NUMBER_INT)) {

    require_once("Controller/ClassificadoController.php");
    require_once("Model/ViewModel/ClassificadoConsulta.php");
    $classificadoController = new ClassificadoController();

    $totalRegistros = $classificadoController->RetornarQuantidadeRegistros(filter_input(INPUT_GET, "cat", FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_GET, "termo", FILTER_SANITIZE_STRING));
    $totalAnunciosPagina = 3; //Alterar para mais

    $paginaAtual = 1;

    if (filter_input(INPUT_GET, "pag", FILTER_SANITIZE_NUMBER_INT)) {
        $paginaAtual = filter_input(INPUT_GET, "pag", FILTER_SANITIZE_NUMBER_INT);
    }

    $fim = ($paginaAtual * $totalAnunciosPagina);
    $inicio = ($fim - $totalAnunciosPagina);

    $listaConsulta = $classificadoController->RetornarPesquisa(filter_input(INPUT_GET, "cat", FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_GET, "termo", FILTER_SANITIZE_STRING), $inicio, $totalAnunciosPagina);
}
?>
<div id="dvCategoria">
    <h1>Categorias</h1>
    <br />

    <?php
    if (count($listaConsulta) > 0) {
        ?>
        <div id="dvCategoriasItens">

            <?php
            foreach ($listaConsulta as $classificadoConsulta) {
                ?>
                <div class="panel grid-100">
                    <div class="grid-30 mobile-grid-100 imgGridCategoria">
                        <a href="?pagina=classificado&cod=<?= $classificadoConsulta->getCod(); ?>"><img src="img/Classificados/<?= $classificadoConsulta->getImagem(); ?>" alt="<?= $classificadoConsulta->getNome(); ?>"/></a>
                    </div>

                    <div class="grid-70 mobile-grid-100 conteudoGridCategoria">
                        <h3><a href="?pagina=classificado&cod=<?= $classificadoConsulta->getCod(); ?>"><?= $classificadoConsulta->getNome(); ?></a></h3>
                        <?= html_entity_decode($classificadoConsulta->getDescricao()); ?>
                        <br/><br/>
                        <p><a href="?pagina=classificado&cod=<?= $classificadoConsulta->getCod(); ?>" class="btnAcessar">Acessar</a></p>
                        <br/>
                    </div>
                    <div clas="clear"></div>
                </div>
                <br />
                <?php
            }
            ?>
        </div>

        <div class="paginacao">
            <ul>

                <?php
                $totalNumeracao = ceil($totalRegistros / $totalAnunciosPagina);
                $currentPage = filter_input(INPUT_GET, "pag", FILTER_SANITIZE_NUMBER_INT);

                $categoria = filter_input(INPUT_GET, "cat", FILTER_SANITIZE_NUMBER_INT);
                $termo = filter_input(INPUT_GET, "termo", FILTER_SANITIZE_STRING);
                for ($i = 0; $i < $totalNumeracao; $i++) {
                    ?>
                    <li><a href="?pagina=categoria&termo=<?= $termo; ?>&cat=<?= $categoria; ?>&pag=<?= ($i + 1); ?>"><?= ($i + 1); ?></a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <?php
    } else {
        echo "Desculpe, não encontramos nenhum classificado com o termo especificado.";
    }
    ?>

    <br />  
</div>