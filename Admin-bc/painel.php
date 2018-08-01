<?php
session_start();

if (isset($_SESSION["logado"])) {
    if (!$_SESSION["logado"]) {
        header("Location: index.php?msg=1");
    }
} else {
    header("Location: index.php?msg=1");
}
?>
<!doctype html>
<html lang="pt-br">
    <head>
        <title>Brasil Classificados - Painel</title>
        <meta charset="utf-8" />
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <script src="../js/jquery-3.1.1.min.js" type="text/javascript"></script>
        <script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <link rel="shortcut icon" href="../img/favicon.ico" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <script src="js/script.js" type="text/javascript"></script>
    </head>
    <body>
        <div id="dvConteudoPrincipal">
            <div class="row" id="dvTopo">
                <div class="col-xs-12 hidden-lg text-center">
                    <div class="dvlogoTopo">
                        <span class="glyphicon glyphicon-menu-hamburger btn btn-default btn-lg" aria-hidden="true" id="btnMenuResponsive"></span>
                        <a href="painel.php"><img src="../img/logoFundoEscuro.png" alt="Logo Brasil Classificados" /></a>
                    </div>
                </div>

                <div class="col-xs-12 hidden-xs">
                    <div class="dvlogoTopo">
                        <a href="painel.php"><img src="../img/logoFundoEscuro.png" alt="Logo Brasil Classificados" /></a>
                    </div>
                </div>
            </div>
            <div class="row" id="dvMenuResponsive" style="display: none;">
                <div class="col-xs-12">
                    <ul id="ulMenuResponsive">
                        <li class="firstLine"><a href="painel.php">Inicio</a></li>
                        <li><a href="?pagina=usuario">Usuário</a></li>
                        <li><a href="?pagina=classificado">Classificados</a></li>
                        <li><a href="?pagina=categoria">Categoria</a></li>
                        <li><a href="?pagina=contato">Contato</a></li>
                        <li><a href="logout.php">Sair</a></li>
                    </ul>
                </div>
            </div>

            <div class="row no-gutter">
                <div class="col-lg-2 gridLeft hidden-xs" id="dvEsquerda">
                    <div id="dvMenuLateral">
                        <ul id="ulMenu">
                            <li class="firstLine"><a href="painel.php">Inicio</a></li>
                            <li><a href="?pagina=usuario">Usuário</a></li>
                            <li><a href="?pagina=classificado">Classificados</a></li>
                            <li><a href="?pagina=categoria">Categoria</a></li>
                            <li><a href="?pagina=contato">Contato</a></li>
                            <li><a href="logout.php">Sair</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-xs-12 col-lg-10" id="dvDireita">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php
                            require_once("../Util/RequestPage.php");
                            ?>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="row" id="dvRodape">
                        <div class="col-lg-6 col-xs-12 alignCenter">
                            <br /><br />
                            <p>&copy; Brasil Classificados - Todos os Direitos Reservados</p>  
                        </div>

                        <div class="col-lg-6 col-xs-12 alignCenter">
                            <a href="#">Facebook</a><br  />
                            <a href="#">Twitter</a><br  />
                            <a href="#">Youtube</a><br  />
                            <a href="#">Instagram</a><br  />
                        </div> 
                    </div>
                    <!--Aqui termina o conteúdo da direita-->
                    <br />
                    <br />
                    <br />
                    <br />
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $("#btnMenuResponsive").click(function () {
                    $("#dvMenuResponsive").slideToggle("slow");
                });
            });
        </script>
    </body>
</html>