<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../lib/PHPMailer/Exception.php';
require_once __DIR__ . '/../lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/SMTP.php';

/**
 * Envía un correo HTML. Si la configuración o el servidor SMTP fallan,
 * registra el problema y devuelve false sin interrumpir la operación principal.
 */
function enviarCorreo(string $destinatario, string $nombreDestinatario, string $asunto, string $cuerpoHtml): bool
{
    $archivoConfig = __DIR__ . '/correo_config.php';
    if (!is_file($archivoConfig)) {
        error_log('No se pudo enviar el correo: falta config/correo_config.php. Copiá correo_config_ejemplo.php y completá los datos localmente.');
        return false;
    }

    try {
        $config = require $archivoConfig;
        if (!is_array($config)) {
            throw new RuntimeException('La configuración SMTP no devolvió un arreglo válido.');
        }

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = (string)($config['host'] ?? 'smtp.gmail.com');
        $mail->Port = (int)($config['puerto'] ?? 587);
        $mail->SMTPSecure = (string)($config['cifrado'] ?? PHPMailer::ENCRYPTION_STARTTLS);
        $mail->SMTPAuth = trim((string)($config['usuario'] ?? '')) !== '';
        $mail->Username = (string)($config['usuario'] ?? '');
        $mail->Password = (string)($config['clave'] ?? '');
        $mail->Timeout = 10;
        $mail->CharSet = 'UTF-8';
        $mail->setFrom(
            (string)($config['remitente'] ?? ''),
            (string)($config['nombre_remitente'] ?? 'Zora')
        );
        $mail->addAddress($destinatario, $nombreDestinatario);
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $cuerpoHtml;
        $mail->AltBody = trim(html_entity_decode(strip_tags($cuerpoHtml), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $mail->send();
        return true;
    } catch (Throwable $e) {
        error_log('No se pudo enviar el correo: ' . $e->getMessage());
        return false;
    }
}
