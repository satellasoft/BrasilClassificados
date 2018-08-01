<?php
//https://github.com/google/recaptcha/blob/1.0.0/php/recaptchalib.php
require_once("Util/recaptchalib.php");
require_once("Util/phpmailer/PHPMailerAutoload.php");


$key = "6LfwITcUAAAAADVQVmfWwcfi_b_ZzI-SufCEmpUA"; //1° 

$reCaptcha = new ReCaptcha($key); //2°

$response = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"], filter_input(INPUT_POST, "g-recaptcha-response")); //3°
$resultado = "&nbsp;";

if ($key != null && $response->success) {
    $emailDestinatario = "brasilclassificados@satellasoft.com"; //Para onde vai a mensagem

    $nome = filter_input(INPUT_POST, "txtNome", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "txtEmail", FILTER_SANITIZE_STRING);

    $assunto = "";
    switch (filter_input(INPUT_POST, "slAssunto", FILTER_SANITIZE_STRING)) {
        case 1:
            $assunto = "Comercial";
            break;

        case 2:
            $assunto = "Suporte";
            break;

        default:
            $assunto = "Outros";
            break;
    }

    $telefone = filter_input(INPUT_POST, "txtTelefone", FILTER_SANITIZE_STRING);
    $mensagem = filter_input(INPUT_POST, "txtMensagem", FILTER_SANITIZE_STRING);
    $ip = $_SERVER["REMOTE_ADDR"];
    $data = date("d/m/Y H:i:s");

    //Começa o PHPMailer

    $mail = new PHPMailer();
    $mail->IsSMTP();

    try {
        $mail->SMTPDebug = 2;
        //https://github.com/PHPMailer/PHPMailer/releases
        //https://github.com/PHPMailer/PHPMailer/wiki/SMTP-Debugging
        $mail->Host = 'mail.satellasoft.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'brasilclassificados@satellasoft.com';
        $mail->Password = 'Br@sil123';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->SMTPSecure = false;
        $mail->SMTPAutoTLS = true;
        $mail->smtpConnect(
                array(
                    "ssl" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                        "allow_self_signed" => true
                    )
                )
        );

        $mail->setFrom($email, $nome); //De quem está enviando
        $mail->addAddress($emailDestinatario, "Contato site");


        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body = $mensagem;
        $mail->AltBody = "Seu navegador não oferece a leitura da mensagem";

        if ($mail->send()) {
            $resultado = "Mensagem enviada com sucesso.";
        } else {
            $resultado = "Houve um erro ao tentar enviar a sua mensagem.";
        }
    } catch (Exception $ex) {
        $resultado = "Houve um erro ao tentar enviar a sua mensagem.";
    }
}
?>

<div id="dvContato">
    <h1>Contato</h1>
    <br />
    <div class="bgcinza padding">
        <p>Você pode entrar em contato com a nossa equipe através das informações forneceidas abaixo, ou se preferir, utilize o formulário de contato.</p>
        <br>
        <p><span class="bold">Telefone: </span> (018) 9898-5888</p>
        <p><span class="bold">Skype: </span> gunnarcorrea</p>
        <p><span class="bold">Whastapp: </span>  (018) 99898-5888</p>
        <p><span class="bold">E-mail: </span> contato(@)satellasoft.com</p>
        <p><span class="bold">Endereço: </span>Rua Santa Cataria N° 1508 | Santana - São Paulo | CEP: 19682-000</p>
    </div>
    <br>
    <div class="formcontroles">
        <!--REST | RESTFULL--->
        <form method="post" id="frmContato">
            <div class="row">
                <div class="grid-50 mobile-grid-100">
                    <label for="txtNome">Nome <span class="red-color">*</span></label>
                    <input type="text" id="txtNome" name="txtNome" placeholder="Nome completo" />
                </div>
                <div class="grid-50 mobile-grid-100">
                    <label for="txtEmail">E-mail  <span class="red-color">*</span><span id="spValidaEmail">&nbsp;</span></label>
                    <input type="text" id="txtEmail" name="txtEmail" placeholder="email@dominio.com" />
                </div>
                <div class="clear"></div>
            </div>
            <br>
            <div class="row">
                <div class="grid-50 mobile-grid-100">
                    <label for="txtTelefone">Telefone</label>
                    <input type="text" id="txtTelefone" name="txtTelefone" placeholder="(018) 998989899" />
                </div>
                <div class="grid-50 mobile-grid-100">
                    <label for="slAssunto">Assunto  <span class="red-color">*</span></label>
                    <select name="slAssunto" id="slAssunto">
                        <option value="1">Comercial</option>
                        <option value="2">Suporte</option>
                        <option value="3">Outros</option>
                    </select>
                </div>
                <div class="clear"></div>
            </div>
            <br>
            <div class="row">
                <div class="grid-100">
                    <label for="txtMensagem">Mensagem (<span id="contadorMensagem" class="bold">500 caracteres restantes</span>) <span class="red-color">*</span></label>
                    <textarea id="txtMensagem" name="txtMensagem"></textarea>
                </div>
                <div class="clear"></div>
            </div>
            <br>
            <div class="row">
                <div class="grid-70 mobile-grid-100">
                    <div class="g-recaptcha" data-sitekey="6LfwITcUAAAAAGMdGFTLEVGqabt52dWrKegFQLpy"  style="float: left;"></div>
                    <script src='https://www.google.com/recaptcha/api.js'></script>  
                </div>

                <div class="grid-30 mobile-grid-100">
                    <input type="submit" name="btnEnviar" id="btnEnviar" value="Enviar" class="btn-padrao"/>
                </div>
                <div class="clear"></div>
            </div>

            <br>
            <div class="row">
                <div class="grid-100">
                    <span id="spResultado"><?= $resultado; ?></span>  
                </div>
            </div>
            <div class="row">
                <ul id="ulErros"></ul>
            </div>
        </form>
    </div>
    <br>
</div>
<script src="js/mask.js" type="text/javascript"></script>
<script>
    $('#txtTelefone').mask('(000) 000000000');

    $("#frmContato").submit(function (event) {
        if (!ValidarFormulario()) {
            event.preventDefault();
        }
    });

    function ValidarFormulario() {
        var ulErros = document.getElementById("ulErros");
        ulErros.style.color = "red";
        ulErros.innerHTML = "";
        var erros = 0;

        if (document.getElementById("txtNome").value.length < 5) {
            erros++;
            ulErros.innerHTML += "<li>- Nome inválido. (Min. 5 caracteres)</li>";
        }

        if (!validateEmail(document.getElementById("txtEmail").value)) {
            erros++;
            ulErros.innerHTML += "<li>- E-mail inválido</li>";
        }

        var assunto = document.getElementById("slAssunto").value;
        if (assunto < 1 || assunto > 3) {
            erros++;
            ulErros.innerHTML += "<li>- Assunto inválido</li>";
        }

        if (document.getElementById("txtMensagem").value.length < 5 || document.getElementById("txtMensagem").value.length > 500) {
            erros++;
            ulErros.innerHTML += "<li>- Mensagem inválida. (min 5 e max. 500 caracteres)</li>";
        }

        var response = grecaptcha.getResponse();
        if (response.length == 0) {
            erros++;
            ulErros.innerHTML += "<li class='bold'>- CAPTCHA não marcado</li>";
        }

        if (erros == 0) {
            return true;
        } else {
            return false;
        }

    }

    $("#txtMensagem").keyup(function () {
        var txtMensagem = $("#txtMensagem").val().length;
        var maximo = 500;
        var total = (parseInt(maximo) - parseInt(txtMensagem));

        $("#contadorMensagem").text(total + " caracteres");

        if (txtMensagem <= maximo) {
            $("#contadorMensagem").css("color", "green");
        } else {
            $("#contadorMensagem").css("color", "red");
        }

    });

    function validateEmail(email)
    {
        //https://stackoverflow.com/questions/46155/how-to-validate-email-address-in-javascript
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }

</script>