<!--
http://www.flaticon.com/free-icon/protest_265749#term=Announce&page=1&position=5
http://www.flaticon.com/free-icon/settings_148912#term=configuration&page=1&position=10
-->
<?php
require_once ("Controller/ClassificadoController.php");
require_once ("Model/Classificado.php");


if (isset($_SESSION["cod"])) {
    $cod = $_SESSION["cod"];
}
$classificadoController = new ClassificadoController();

$listaClassificado = $classificadoController->RetornarUsuarioCod($cod);
?>
<div id="dvPainel">
    <h1>Gerenciar minha conta</h1>
    <br />
    <?php
    if ($cod != 0) {
        ?>
        <div class="menuPainel">
            <div>
                <a href="?pagina=anunciar">
                    <img src="img/icones/anuncio.png" alt="Anunciar" /><br>
                    Anunciar
                </a>
            </div>
            <div>
                <a href="?pagina=configuracao">
                    <img src="img/icones/configuracao.png" alt="Configuração" /><br>
                    Configuração
                </a>
            </div>
        </div>
        <br>
        <p class="bold">Meus anúncios</p>
        <div id="dvConsultaAnuncios">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Valor</th>
                        <th>Categoria</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($listaClassificado as $classificado) {
                        ?>
                        <tr>
                            <td><?= $classificado->getNome(); ?></td>
                            <td>R$ <?= number_format($classificado->getValor(), 2, ",", "."); ?></td>
                            <td><?= $classificado->getCategoria()->getNome(); ?></td>
                            <td>
                                <a href="?pagina=anunciar&cod=<?= $classificado->getCod(); ?>" class="button">Editar</a><br>
                                <a href="?pagina=visualizarclassificado&cod=<?= $classificado->getCod(); ?>" class="button">Visualizar</a><br>
                                <a href="?pagina=anexarimagem&cod=<?= $classificado->getCod(); ?>" class="button">inserir imagens</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
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
