<?php

use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                
    $mail->SMTPAuth   = true;                                  
    $mail->Username   = 'ryunakato6@gmail.com';                    
    $mail->Password   = 'fibx uygw fmxh sxct';                               
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           
    $mail->Port       = 465;                                 

    //Recipients
    $mail->setFrom('ryunakato6@gmail.com', 'TIENDA CM');
    $mail->addAddress('varulv1243@gmail.com', 'Joe User');     

    //Content
    $mail->isHTML(true); 
    $mail->Subject = 'Detalles de su comprra';
    $cuerpo = '<h4> Gracias por su compra </h4>';
    $cuerpo .='<p>El ID de su compra es <b>'. $id_transaccion .'</b></p>';

    $mail->Body    = utf8_decode($cuerpo);
    $mail->AltBody = 'Le enviamos los detalles de su compra';

    $mail->setLanguage('es','../phpmailer/language/phpmailer.lang-es');

    $mail->send();
} catch (Exception $e) {
    echo "Error al enviar el correo electronico de la compra: {$mail->ErrorInfo}";
}