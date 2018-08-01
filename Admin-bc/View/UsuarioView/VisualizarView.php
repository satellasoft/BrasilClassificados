<?php
require_once ("../Controller/UsuarioController.php");
require_once ("../Model/Usuario.php");

require_once ("../Controller/EnderecoController.php");
require_once ("../Model/Endereco.php");

require_once ("../Controller/TelefoneController.php");
require_once ("../Model/Telefone.php");


$usuarioController = new UsuarioController();
$enderecoController = new EnderecoController();
$telefoneController = new TelefoneController();

//Usuário
$usuario = $usuarioController->RetornaCod(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));
$date = str_replace('/', '-', $usuario->getNascimento());
$date = date('d-m-Y', strtotime($date));

//Endereco
$listaEndereco = $enderecoController->RetornarTodosUsuarioCod(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));
$arraySiglas = array("ac", "al", "am", "ap", "ba", "ce", "df", "es", "go", "ma", "mt", "ms", "mg", "pa", "pb", "pr", "pe", "pi", "rj", "rn", "ro", "rs", "rr", "sc", "se", "sp", "to");
$arrayNomes = array("Acre", "Alagoas", "Amazonas", "Amapá", "Bahia", "Ceará", "Distrito Federal", "Espírito Santo", "Goiás", "Maranhão", "Mato Grosso", "Mato Grosso do Sul", "Minas Gerais", "Pará", "Paraíba", "Paraná", "Pernambuco", "Piauí", "Rio de Janeiro", "Rio Grande do Norte", "Rondônia", "Rio Grande do Sul", "Roraima", "Santa Catarina", "Sergipe", "São Paulo", "Tocantins");

//Telefone
$listaTelefone = $telefoneController->RetornarTodosUsuario(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));
?>

<div id="dvVisualizarView">
    <h1>Visualizar usuário</h1>
    <br />
    <!--DIV CADASTRO -->
    <div class="panel panel-default maxPanelWidth">
        <div class="panel-heading">Dados pessoais</div>
        <div class="panel-body">
            <p><span class="bold">Nome:</span> <?= $usuario->getNome(); ?> <span class="bold">E-mail:</span> <?= $usuario->getEmail(); ?></p>
            <p><span class="bold">CPF:</span> <?= $usuario->getCpf(); ?> <span class="bold">Usuário:</span> <?= $usuario->getUsuario(); ?></p>
            <p><span class="bold">Data nascimento:</span> <?= $date; ?> <span class="bold">Sexo:</span> <?= ($usuario->getSexo() == "m" ? "Masculino" : "Feminino"); ?></p>
            <p><span class="bold">Status:</span> <?= ($usuario->getStatus() == 1 ? "Ativo" : "Bloqueado"); ?> <span class="bold">Permissão: </span> <?= ($usuario->getPermissao() == 1 ? "Administrador" : "Comum"); ?> <span class="bold">IP:</span> <?= $usuario->getIp(); ?></p>

        </div>
    </div>
    <br />

    <div class="panel panel-default maxPanelWidth">
        <div class="panel-heading">Endereços</div>
        <div class="panel-body">

            <?php
            foreach ($listaEndereco as $endereco) {
                $estado = "";
                $count = 0;
                foreach ($arraySiglas as $arr) {
                    if ($arr == $endereco->getEstado()) {
                        $estado = $arrayNomes[$count];
                    }

                    $count++;
                }
                ?>
                <p><span class="bold">Rua:</span> <?= $endereco->getRua(); ?> <span class="bold">Número: </span> <?= $endereco->getNumero(); ?> <span class="bold">Complemento: </span> <?= $endereco->getComplemento(); ?></p>
                <p><span class="bold">Cidade:</span> <?= $endereco->getCidade(); ?> <span class="bold">Bairro: </span> <?= $endereco->getBairro(); ?> </p>
                <p><span class="bold">Estado:</span> <?= $estado; ?> <span class="bold">CEP: </span> <?= $endereco->getCep(); ?> </p>
                <hr />
                <?php
            }
            ?>
        </div>
    </div>
    <br />
    <div class="panel panel-default maxPanelWidth">
        <div class="panel-heading">Telefones</div>
        <div class="panel-body">

            <?php
            foreach ($listaTelefone as $telefone) {

                $tipoTelefone = "Celular";

                if ($telefone->getTipo() == 2) {
                    $tipoTelefone = "Telfone";
                } else if ($telefone->getTipo() == 3) {
                    $tipoTelefone = "Fax";
                }
                ?>
                <p><span class="bold">Número:</span> <?= $telefone->getNumero(); ?> <span class="bold">Tipo:</span> <?= $tipoTelefone; ?></p>
                <hr />
                <?php
            }
            ?>

        </div>
    </div>

</div>
