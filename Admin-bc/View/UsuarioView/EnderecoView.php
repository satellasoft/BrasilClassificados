<?php
require_once("../Model/Endereco.php");
require_once("../Controller/EnderecoController.php");
$cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);

$rua = "";
$numero = "";
$cep = "";
$cidade = "";
$complemento = "";
$bairro = "";
$estado = "ac";
$enderecoController = new EnderecoController();

$arraySiglas = array("ac", "al", "am", "ap", "ba", "ce", "df", "es", "go", "ma", "mt", "ms", "mg", "pa", "pb", "pr", "pe", "pi", "rj", "rn", "ro", "rs", "rr", "sc", "se", "sp", "to");
$arrayNomes = array("Acre", "Alagoas", "Amazonas", "Amapá", "Bahia", "Ceará", "Distrito Federal", "Espírito Santo", "Goiás", "Maranhão", "Mato Grosso", "Mato Grosso do Sul", "Minas Gerais", "Pará", "Paraíba", "Paraná", "Pernambuco", "Piauí", "Rio de Janeiro", "Rio Grande do Norte", "Rondônia", "Rio Grande do Sul", "Roraima", "Santa Catarina", "Sergipe", "São Paulo", "Tocantins");

if (filter_input(INPUT_POST, "btnGravar", FILTER_SANITIZE_STRING)) {
    $endereco = new Endereco();

    $endereco->setCod(filter_input(INPUT_GET, "codedit", FILTER_SANITIZE_NUMBER_INT));
    $endereco->setRua(filter_input(INPUT_POST, "txtRua", FILTER_SANITIZE_STRING));
    $endereco->setCidade(filter_input(INPUT_POST, "txtCidade", FILTER_SANITIZE_STRING));
    $endereco->setComplemento(filter_input(INPUT_POST, "txtComplemento", FILTER_SANITIZE_STRING));
    $endereco->setEstado(filter_input(INPUT_POST, "slEstado", FILTER_SANITIZE_STRING));
    $endereco->getUsuario()->setCod(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));
    $endereco->setCep(filter_input(INPUT_POST, "txtCep", FILTER_SANITIZE_STRING));
    $endereco->setBairro(filter_input(INPUT_POST, "txtBairro", FILTER_SANITIZE_STRING));
    $endereco->setNumero(filter_input(INPUT_POST, "txtNumero", FILTER_SANITIZE_STRING));

    if (!filter_input(INPUT_GET, "codedit", FILTER_SANITIZE_NUMBER_INT)) {
        if ($enderecoController->Cadastrar($endereco)) {
            ?>
            <script>
                document.cookie = "msg=1";
                document.location.href = "?pagina=endereco&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>";
            </script>
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar cadastrar o endereço.</div>";
        }
    } else {
        //Editar
          if ($enderecoController->Alterar($endereco)) {
            ?>
            <script>
                document.cookie = "msg=2";
                document.location.href = "?pagina=endereco&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>";
            </script>
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar alterar o endereço.</div>";
        }
    }
}

if (filter_input(INPUT_GET, "coddel", FILTER_SANITIZE_NUMBER_INT)) {
    if ($enderecoController->Deletar(filter_input(INPUT_GET, "coddel", FILTER_SANITIZE_NUMBER_INT))) {
        ?>
        <script>
            document.cookie = "msg=3";
            document.location.href = "?pagina=endereco&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>";
        </script>
        <?php
    }
}

$resultado = "";
$listaEndereco = [];
if (filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT)) {
    $listaEndereco = $enderecoController->RetornarTodosUsuarioCod(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));
}

if (filter_input(INPUT_GET, "codedit", FILTER_SANITIZE_NUMBER_INT)) {

    $retornoCod = $enderecoController->RetornarCod(filter_input(INPUT_GET, "codedit", FILTER_SANITIZE_NUMBER_INT));

    if ($retornoCod != null) {
        $rua = $retornoCod->getRua();
        $numero = $retornoCod->getNumero();
        $cep = $retornoCod->getCep();
        $cidade = $retornoCod->getCidade();
        $complemento = $retornoCod->getComplemento();
        $bairro = $retornoCod->getBairro();
        $estado = $retornoCod->getEstado();
    }
}
?>
<div id="dvEnderecoView">
    <h1>Gerenciar Endereço</h1>
    <br />
    <!--DIV CADASTRO -->
    <div class="panel panel-default maxPanelWidth">
        <div class="panel-heading">Cadastrar e editar</div>
        <div class="panel-body">
            <form method="post" id="frmGerenciarEndereco" name="frmGerenciarEndereco" novalidate>

                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="txtCep">CEP </label>
                            <input type="text" class="form-control" id="txtCep" name="txtCep" value="<?= $cep; ?>" />
                        </div>

                    </div>


                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">

                            <input type="hidden" id="txtCodUsuario" value="<?= $cod; ?>" />
                            <label for="txtRua">Rua</label>
                            <input type="text" class="form-control" id="txtRua" name="txtRua" placeholder="" value="<?= $rua; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="txtCidade">Cidade</label>
                            <input type="text" class="form-control" id="txtCidade" name="txtCidade" placeholder=""  value="<?= $cidade; ?>">
                        </div>
                    </div>

                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="txtComplemento">Complemento</label>
                            <input type="text" class="form-control" id="txtComplemento" name="txtComplemento" placeholder=""  value="<?= $complemento; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-xs-12">
                        <div class="form-group">
                            <label for="txtNumero">Número</label>
                            <input type="text" class="form-control" id="txtNumero" name="txtNumero" placeholder=""  value="<?= $numero; ?>">
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-12">
                        <div class="form-group">
                            <label for="txtBairro">Bairro</label>
                            <input type="text" class="form-control" id="txtBairro" name="txtBairro" placeholder=""  value="<?= $bairro; ?>">
                        </div>
                    </div>

                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <!--http://satellasoft.com/?materia=select-com-os-estados-brasileiros-->
                            <label for="slEstado">Estado</label>
                            <select class="form-control" id="slEstado" name="slEstado">
                                <?php
                                for ($i = 0; $i < count($arrayNomes); $i++) {
                                    ?>
                                    <option value="<?= $arraySiglas[$i] ?>" <?= ($arraySiglas[$i] == $estado ? "selected='selected'" : "") ?>><?= $arrayNomes[$i] ?></option> 
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <p id="pResultado"><?= $resultado; ?></p>
                    </div>
                </div>
                <input class="btn btn-success" type="submit" name="btnGravar" value="Gravar">
                <a href="?pagina=endereco&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>" class="btn btn-danger">Cancelar</a>

                <br />
                <br />
                <div class="row">
                    <div class="col-lg-12">
                        <ul id="ulErros"></ul>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <br /><br />
    <?php
    if ($listaEndereco != null) {
        foreach ($listaEndereco as $end) {
            ?>
            <div class="panel panel-info maxPanelWidth">
                <div class="panel-heading"><?= $end->getUsuario()->getNome(); ?></div>
                <div class="panel-body">
                    <ul>
                        <li><span class="bold">Rua:</span> <?= $end->getRua(); ?></li>
                        <li><span class="bold">Número:</span> <?= $end->getNumero(); ?></li>
                        <li><span class="bold">Cidade:</span> <?= $end->getCidade(); ?></li>
                        <li><span class="bold">Estado:</span> <?= strtoupper($end->getEstado()); ?></li>
                        <li><span class="bold">Bairro:</span> <?= $end->getBairro(); ?></li>
                        <li><span class="bold">CEP:</span> <?= $end->getCep(); ?></li>
                        <li><span class="bold">Complemento:</span> <?= $end->getComplemento(); ?></li>
                    </ul>
                    <a href="?pagina=endereco&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>&coddel=<?= $end->getCod(); ?>" class="btn btn-danger" onclick="return confirm('Deseja realmente remover?');">Remover</a>
                    <a href="?pagina=endereco&cod=<?= filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT) ?>&codedit=<?= $end->getCod(); ?>" class="btn btn-warning">Editar</a>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>

<script src="../js/mask.js" type="text/javascript"></script>

<script>
                        //http://api.postmon.com.br/v1/cep/19050530
                        $(document).ready(function () {

                            if (getCookie("msg") == 1) {
                                document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Endereço cadastrado com sucesso.</div>";
                                document.cookie = "msg=d";
                            } else if (getCookie("msg") == 2) {
                                document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Endereço alterado com sucesso.</div>";
                                document.cookie = "msg=d";
                            } else if (getCookie("msg") == 3) {
                                document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Endereço deletado com sucesso.</div>";
                                document.cookie = "msg=d";
                            }

                            $('#txtCep').mask('00000-000');

                            $("#frmGerenciarEndereco").submit(function (event) {
                                if (!Validar()) {
                                    event.preventDefault();
                                }
                            });

                            $("#txtCep").focusout(function () {
                                if ($("#txtCep").val().length > 0) {
                                    var link = "http://api.postmon.com.br/v1/cep/" + $("#txtCep").val();

                                    link = link.replace("-", "");

                                    $.ajax({
                                        url: link,
                                        dataType: "json",
                                        type: "get",
                                        data: {},
                                        success: function (ret) {
                                            console.log(ret);
                                            $("#txtBairro").val(ret.bairro);
                                            $("#txtCidade").val(ret.cidade);
                                            $("#txtRua").val(ret.logradouro);
                                            $("#slEstado option[value='" + ret.estado.toLowerCase() + "']").attr("selected", "selected");
                                        },
                                        error: function (erro) {
                                            console.log(erro);
                                        }
                                    });
                                }
                            });
                        });

                        //Validação

                        function Validar() {
                            var erros = 0;

                            var ulErros = document.getElementById("ulErros");
                            ulErros.style.color = "red";
                            ulErros.innerHTML = "";

                            if ($("#txtRua").val().length <= 0) {
                                erros++;
                                var li = document.createElement("li");
                                li.innerHTML = "Informe um nome de rua";
                                ulErros.appendChild(li);
                            }

                            if ($("#txtNumero").val().length <= 0) {
                                erros++;
                                var li = document.createElement("li");
                                li.innerHTML = "Informe o número da residência";
                                ulErros.appendChild(li);
                            }

                            if ($("#txtBairro").val().length <= 0) {
                                erros++;
                                var li = document.createElement("li");
                                li.innerHTML = "Informe o bairro";
                                ulErros.appendChild(li);
                            }

                            if ($("#txtCidade").val().length <= 0) {
                                erros++;
                                var li = document.createElement("li");
                                li.innerHTML = "Informe o nome da cidade";
                                ulErros.appendChild(li);
                            }

                            if ($("#txtComplemento").val().length <= 0) {
                                erros++;
                                var li = document.createElement("li");
                                li.innerHTML = "Informe o tipo de complemento";
                                ulErros.appendChild(li);
                            }

                            if ($("#txtCep").val().length !== 9) {
                                erros++;
                                var li = document.createElement("li");
                                li.innerHTML = "Informe um CEP válido";
                                ulErros.appendChild(li);
                            }

                            if (!ValidarEstado($("#slEstado").val())) {
                                erros++;
                                var li = document.createElement("li");
                                li.innerHTML = "Informe um estado válido";
                                ulErros.appendChild(li);
                            }


                            if (erros === 0) {
                                return true;
                            } else {
                                return false;
                            }
                        }

                        function ValidarEstado(ende) {
                            var achou = false;
                            var arrayEstados = ["ac", "al", "am", "ap", "ba", "ce", "df", "es", "go", "ma", "mt", "ms", "mg", "pa", "pb", "pr", "pe", "pi", "rj", "rn", "ro", "rs", "rr", "sc", "se", "sp", "to"];
                            for (var i = 0; i < arrayEstados.length; i++) {
                                if (arrayEstados[i] == ende) {
                                    achou = true;
                                }
                            }

                            return achou;
                        }
</script>