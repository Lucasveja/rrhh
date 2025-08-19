<?
require_once('AttachMailer.php'); 

$mailer = new AttachMailer("correo@correo.com", "castro_gf@yahoo.com.ar", "asunto", "hello contenido del mensaje");
$mailer->attachFile($_SERVER['DOCUMENT_ROOT']."/info.php");
$mailer->send() ? "Enviado": "Problema al enviar";
?>