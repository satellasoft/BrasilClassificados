<?php
require_once("../Util/UploadFile.php");
require_once("../Controller/CategoriaController.php");
$categoriaController = new CategoriaController();
$resultado = " ";

if (filter_input(INPUT_POST, "btnAlterarImagem", FILTER_SANITIZE_STRING)) {
    $upload = new Upload();
    $nomeImagem = $upload->LoadFile("../img/Categorias/", "img", $_FILES["flImagem"]);
    if ($nomeImagem != "" && $nomeImagem != "invalid") {
        if ($categoriaController->AlterarImagem($nomeImagem, filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT))) {
            unlink("../img/Categorias/" . filter_input(INPUT_GET, "img", FILTER_SANITIZE_STRING));
            ?>
            <script>
                document.cookie = "msg=1";
                document.location.href = "?pagina=categoriaimagem&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT); ?>&img=<?= $nomeImagem ?>";
            </script>
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar alterar a imagem.</div>";
            unlink("../img/Categorias/{$nomeImagem}");
        }
    } else if ($nomeImagem == "invalid") {
        $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Formato de imagem inválido.</div>";
    } else {
        $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar carregar a imagem.</div>";
    }
}
?>
<div id="dvImagemCategoriaView">
    <h1>Alterar imagem</h1>
    <br />

    <div class="panel panel-default maxPanelWidth">
        <div class="panel-heading">&nbsp;</div>
        <div class="panel-body">
            <form method="post" id="frmAlterarImagemCategoria" name="frmAlterarImagemCategoria" novalidate enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <img src="../img/Categorias/<?= filter_input(INPUT_GET, "img", FILTER_SANITIZE_STRING); ?>" alt="" title="" class="img-responsive img-thumbnail" />
                        <br /> <br />
                    </div>
                    <div class="col-lg-6 col-xs-12">
                        <p>Selecione uma nova imagem para a alterção.</p>
                        <br />
                        <div class="form-group">
                            <label for="flImagem">Selecione uma imagem</label>
                            <input type="file" id="flImagem" name="flImagem" accept="image/*"/>
                        </div>
                        <input class="btn btn-success" type="submit" name="btnAlterarImagem" value="Alterar imagem">
                        <span id="spResultado" class="bold"><?= $resultado; ?></span>
                    </div>
                    <div class="clear"></div>
                    <br />
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        if (getCookie("msg") == 1) {
            document.getElementById("spResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Imagem alterada com sucesso.</div>";
            document.cookie = "msg=d";
        }


        $("#frmAlterarImagemCategoria").submit(function (e) {
            if ($("#flImagem").val() === "") {
                $("#spResultado").text("Selecione uma imagem.");
                $("#spResultado").css("color", "red");

                e.preventDefault();
            }
        });
    });
</script>