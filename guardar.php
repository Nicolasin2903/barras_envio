<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "codigos";

$conn = new mysqli($servername, $username, $password, $dbname);



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Recibir datos del index
$nombre = $_POST['nombre'];
$codigo = $_POST['codigo'];
$barcodeImage = $_POST['barcodeImage'];

// Decodificar la imagen en base64
list($type, $barcodeImage) = explode(';', $barcodeImage); // Separa la imagen type = img/png y barcode recibe el valor de la imagen en base64
list(, $barcodeImage) = explode(',', $barcodeImage); // Separa barcode con la coma, primera parte es el prefijo base64, segunda parte la cadena 
$barcodeImage = base64_decode($barcodeImage); // base64_decode convierte el dato final a bianarios


$mail = new PHPMailer(true);
try {
    // Configuración del servidor
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'diego.teran@ugc.edu.co';
    $mail->Password = '091705Datm@';               
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587; 

    $mail->setFrom('diego.andres@ucg.edu.co'); //Remintente
    $mail->addAddress('dmartinezteran560@gmail.com'); //Destinatario

    // Contenido del correo
    $mail->isHTML(true);                                    // Establecer formato de correo en HTML
    $mail->Subject = 'Código de Barras Generado';
    $mail->Body = "<h1>CODIGO DE BARRAS</h1> 
<div>Nombre del estudiante: $nombre</div>
<div>Código del producto: $codigo</div>
<div><img src='cid:barcode_image'></div>";

    
    // Agregar la imagen del código de barras
    $mail->addStringEmbeddedImage($barcodeImage, 'barcode_image', 'codigo_barras.png', 'base64', 'image/png');

    // Enviar el correo
    $mail->send();
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $mail->ErrorInfo]);
}
