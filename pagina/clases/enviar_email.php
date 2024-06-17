<?php

use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      
    $mail->isSMTP();                                            
    $mail->Host       = MAIL_HOST;                
    $mail->SMTPAuth   = true;                                  
    $mail->Username   = MAIL_USER;                    
    $mail->Password   = MAIL_PASS;                               
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           
    $mail->Port       = MAIL_PORT;                                 

    //correo emisor y nombre
    $mail->setFrom(MAIL_USER, 'TIENDA CM');
    //correo receptor y nombre
    $mail->addAddress('varulv1243@gmail.com', 'Joe User');
    //enviar copia correo
    $mail->addReplyTo('varulv1243@gmail.com');        

    //Content
    $mail->isHTML(true); 
    $mail->Subject = 'Detalles de su comprra';
    $cuerpo = '<h4> Gracias por su compra </h4>';
    $cuerpo .='<p>El ID de su compra es <b>'. $id_transaccion .'</b></p>';

    $mail->Body = mb_convert_encoding($cuerpo, 'ISO-8859-1', 'UTF-8');
    $mail->AltBody = 'Le enviamos los detalles de su compra';

    $mail->setLanguage('es','../phpmailer/language/phpmailer.lang-es');

    $mail->send();
} catch (Exception $e) {
    echo "Error al enviar el correo electronico de la compra: {$mail->ErrorInfo}";
}