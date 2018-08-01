<?php
require_once("../Util/UploadMultipleFile.php");
require_once("../Controller/ImagemController.php");
require_once("../Model/Imagem.php");

$uploadMultipleFile = new Upload();
$imagemController = new ImagemController();

$resultado = "";

$cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);

if (filter_input(INPUT_POST, "btnCarregar", FILTER_SANITIZE_STRING)) {

    $arquivos = $uploadMultipleFile->LoadFile("../img/Classificados/", $_FILES["flImagem"]);
    $codClassificado = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);

    $listaImagem = [];
    foreach ($arquivos as $nome) {
        //     
        $imagem = new Imagem();
        $imagem->getClassificado()->setCod($codClassificado);
        $imagem->setImagem($nome);

        $listaImagem[] = $imagem;
    }

    if ($imagemController->CadastrarImagens($listaImagem)) {
        ?>
        <script>
            document.cookie = "msg=1";
            document.location.href = "?pagina=gerenciarimagemclassificado&cod=<?= $codClassificado; ?>";
        </script>
        <?php
    } else {
        foreach ($arquivos as $nome) {
            unlink("../img/Classificados/{$nome}");
        }
        $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar cadastrar as imagens.</div>";
    }

    if ($uploadMultipleFile->ValidaImagens($_FILES["flImagem"], "img", 2, 10)) {
        
    } else {
        $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar carregar imagens, por favor, verifique o tamanho, extensão e a quantidade dos arquivos.</div>";
    }
}

if (filter_input(INPUT_GET, "del", FILTER_SANITIZE_NUMBER_INT)) {
    $nomeImagem = $imagemController->VerificarArquivoExiste(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_GET, "del", FILTER_SANITIZE_NUMBER_INT));
    if ($nomeImagem != "" || $nomeImagem != null) {
        if ($imagemController->RemoverImagem(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_GET, "del", FILTER_SANITIZE_NUMBER_INT))) {
            unlink("../img/Classificados/{$nomeImagem}");
            ?>
            <script>
                document.cookie = "msg=2";
                document.location.href = "?pagina=gerenciarimagemclassificado&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT); ?>";
            </script>
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar remover a imagem.</div>";
        }
    } else {
        $resultado = "<div class=\"alert alert-danger\" role=\"alert\">O arquivo informado não pode ser localizado.</div>";
    }
}

$listaImagem = $imagemController->CarregarImagensClassificado(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));
?>
<div id="dvImagensClassificadoView">
    <h1>Gerenciar imagens do classificado</h1>
    <br />

    <div class="panel panel-default maxPanelWidth">
        <div class="panel-heading">Carregar imagens</div>
        <div class="panel-body">
            <form method="post" id="frmGerenciarImagensClassificado" name="frmGerenciarImagensClassificado"  enctype="multipart/form-data">
                <div class="row">
                    <div class=" col-xs-12">
                        <div class="form-group">
                            <label for="flImagem">Selecione as imagens (Máximo 6)</label>
                            <input type="file" id="flImagem" name="flImagem[]" accept="image/*" multiple="multitple" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <p class="bold">Imagens selecionadas</p>
                        <ul id="ulImagensSelecionadas"></ul>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <input class="btn btn-success" type="submit" name="btnCarregar" value="Carregar">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <br />
                        <span id="spResultado"><?= $resultado; ?></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <br  />

    <div class="panel panel-default maxPanelWidth">
        <div class="panel-heading">Imagens</div>
        <div class="panel-body">
            <!--Aqui ven as imagens do classificado-->
            <table class="table table-hover table-responsive table-striped">
                <thead>
                    <tr>
                        <th>Imagem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($listaImagem != null) {
                        foreach ($listaImagem as $imagem) {
                            ?>
                            <tr>
                                <td><img src="../img/Classificados/<?= $imagem->getImagem(); ?>" alt="Imagem produto" class="imgClassificado" />
                                    <br /> <br />
                                    <a href="?pagina=gerenciarimagemclassificado&cod=<?=$cod;?>&del=<?= $imagem->getCod(); ?>" class="btn btn-danger">Remover</a></td>
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

<script>
    //http://stackoverflow.com/questions/6171013/javascript-get-number-of-files-and-their-filenames-from-file-input-element-with
    $(document).ready(function () {
        if (getCookie("msg") == 1) {
            document.getElementById("spResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Imagens cadastradas com sucesso.</div>";
            document.cookie = "msg=d";
        } else if (getCookie("msg") == 2) {
            document.getElementById("spResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Imagem removida com sucesso.</div>";
            document.cookie = "msg=d";
        }



        $("#flImagem").change(function () {
            var inp = document.getElementById('flImagem');
            var ulImagensSelecionadas = document.getElementById("ulImagensSelecionadas");
            ulImagensSelecionadas.innerHTML = "";

            for (var i = 0; i < inp.files.length; ++i) {
                var name = inp.files.item(i).name;
                var li = document.createElement("li");
                li.innerText = "- " + name;
                ulImagensSelecionadas.appendChild(li);
            }
        });


        $("#frmGerenciarImagensClassificado").submit(function (event) {
            var inp = document.getElementById('flImagem');
            if (inp.files.length <= 6) {
                //Valido
            } else {
                document.getElementById("spResultado").innerText = "Selecione no máximo seis imagens!";
                event.preventDefault();
            }
        });
    });
</script>