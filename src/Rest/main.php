<?php
error_reporting(0); // disable errors
ini_set('max_execution_time', 0); //0=NOLIMIT
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);



$Connection  = new Connection();
$db          = $Connection->getConnection();
require '../src/lib/PHPMailer/Exception.php';
require '../src/lib/PHPMailer/PHPMailer.php';
require '../src/lib/PHPMailer/SMTP.php';
require "../src/Rest/Controller/CtrlUsuario.php";



 function enviarCorreo($body, $subject, $email) {

        try {
            $mail = new PHPMailer(true); // Passing `true` enables exceptions
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'ti@frioexpress.com';                 // SMTP username
            $mail->Password = 'frioexp378';                           // SMTP password                                // TCP port to connect to
            $mail->Port = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->CharSet = 'UTF-8';
            //Recipients
            $mail->setFrom('ti@frioexpress.com');
            foreach ($email as $value) {
                $mail->addAddress($value);    // Add a recipient
            }
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
                        //$mail->Body = $this->estiloHTMLCorreo($titulo, $body);
        $mail->Body = '<html lang="es">
                        <head>
                                <meta name="viewport" content="width=device-width" />
                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                <title>Sistema de autoconsumo</title>
                        </head>

                        <body>
                                '.$body.'
                        </body>

                        </html>';
            if(!$mail->send()) {
                $result['error'] = true;
                $result['msg'] = '¡ERROR! No se envío el correo electrónico: '.$mail->ErrorInfo;
            } else {
                $result['error'] = false;
            }
        }  catch (Exception $e) {
            $result['error'] = true;
            $result['msg'] = '¡ERROR! No se envío el correo electrónico: '.$e->getMessage();
        }

        return $result;

 }

function showResponse($code, $result, $response) {
    switch ($code) {
        case 200:
            $response = $response->withJson($result);
            break;
        case 400:
            $response = $response->withJson($result);
            break;
        case 401:
            $response = $response->withJson($result);
            break;
        case 404:
            $response = $response->withJson($result);
            break;
        case 500:
            $cuerpo = "<h2>Error</h2><p>".json_encode($result)."</p>";
            $asunto = 'Error sistema de autoconsumo';
            $emails = array();
            array_push($emails, 'darmando.lira@gmail.com');
            $envioCorreo = enviarCorreo($cuerpo, $asunto, $emails);
            $response = $response->withJson($result);
            break;
    }

    $response = $response->withStatus($code);
    return $response;
}

?>
