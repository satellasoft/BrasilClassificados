<?php
require_once("Util/UploadMultipleFile.php");
require_once("Controller/ImagemController.php");
require_once("Model/Imagem.php");

$uploadMultipleFile = new Upload();
$imagemController = new ImagemController();
$cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
$resultado = "";

if (filter_input(INPUT_POST, "btnCarregar", FILTER_SANITIZE_STRING)) {

    if ($uploadMultipleFile->ValidaImagens($_FILES["flImagem"], "img", 1, 6)) {

        $arquivos = $uploadMultipleFile->LoadFile("img/Classificados/", $_FILES["flImagem"]);
        $codClassificado = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);

        $listaImagem = [];
        foreach ($arquivos as $nome) {
            //
            $imagem = new Imagem();
            $imagem->getClassificado()->setCod($cod);
            $imagem->setImagem($nome);

            $listaImagem[] = $imagem;
        }

        if ($imagemController->CadastrarImagens($listaImagem)) {
            $resultado = "<div style='color: green;'>Imagens carregadas com sucesso.</div>";
        } else {
            foreach ($arquivos as $nome) {
                unlink("img/Classificados/{$nome}");
            }
            $resultado = "<div style='color: red;'>Houve um erro ao tentar cadastrar as imagens.</div>";
        }
    } else {
        $resultado = "<div style='color: red;'>Houve um erro ao tentar carregar imagens, por favor, verifique o tamanho, extensão e a quantidade dos arquivos.</div>";
    }
}
?>
<div id="dvAnexarImagem">
    <h1>Anexar imagem</h1>
    <br />
    <?php
    if (isset($_SESSION["cod"])) {
        $listaImagem = $imagemController->RetornarImagensClassificado($cod, $_SESSION["cod"]);
        ?>
        <div id="dvFormularioAnexarImagem" class="formcontroles">
            <form method="post" id="frmAnexarImagem" name="frmAnexarImagem"  enctype="multipart/form-data">
                <div class="linha">
                    <div class="grid-100 coluna">
                        <label for="flImagem">Selecione as imagens (Máximo 6 imagens de 1 MB cada)</label>
                        <input type="file" id="flImagem" name="flImagem[]" accept="image/*" multiple="multitple" />
                    </div>
                </div>

                <div class="linha">
                    <div class="grid-100 coluna">
                        <p class="bold">Imagens selecionadas</p>
                        <ul id="ulImagensSelecionadas"></ul>
                    </div>
                </div>

                <div class="linha">
                    <div class="grid-100 coluna right">
                        <input type="submit" name="btnCarregar" id="btnCarregar" value="Carregar" class="btn-padrao" />
                    </div>
                </div>
                <div class="linha">
                    <div class="grid-100 coluna">
                        <br />
                        <span id="spResultado"><?= $resultado; ?></span>
                    </div>
                </div>
            </form>
        </div>
        <br>

<?php
if($listaImagem != null){
 ?>
        <div id="dvImagensClassificadoAutor">
            <h2><?= $listaImagem[0][1]; ?></h2>
            <ul>
                <?php
                foreach ($listaImagem as $dr) {
                    ?>
                    <li>
                        <img src="img/classificados/<?= $dr[0]; ?>" alt="Imagem Tópico" onclick="OpenModal('<?= $dr[0]; ?>');"/>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <?php
      }
    } else {
        ?>
        <p>Você precisa estar autenticado para acessar este conteúdo, clique <a href="?pagina=entrar">aqui</a> fazer o login.</p>
        <br>
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

<script>
    function OpenModal(image) {
        $("#dvModal").show("normal");
        document.getElementById("imgModal").src = "img/classificados/" + image;
    }
    function CloseModal() {
        $("#dvModal").hide("normal");
    }


    //http://stackoverflow.com/questions/6171013/javascript-get-number-of-files-and-their-filenames-from-file-input-element-with
    $(document).ready(function () {
//        if (getCookie("msg") == 1) {
//            document.getElementById("spResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Imagens cadastradas com sucesso.</div>";
//            document.cookie = "msg=d";
//        } else if (getCookie("msg") == 2) {
//            document.getElementById("spResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Imagem removida com sucesso.</div>";
//            document.cookie = "msg=d";
//        }

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


        $("#frmAnexarImagem").submit(function (event) {
            var inp = document.getElementById('flImagem');
            if (inp.files.length >= 1 && inp.files.length <= 6) {
                //Valido
            } else {
                document.getElementById("spResultado").style.color = "red";
                document.getElementById("spResultado").innerText = "Selecione no máximo seis imagens e no mínimo 1!";
                event.preventDefault();
            }
        });
    });
</script>
