<?php
namespace App\Constants;

abstract class Messages {
    // -- Commands -- //
    const COMMAND_NOT_FOUND = "Comando no válido";

    // -- Email -- //
    const EMAIL_SUBJECT = "Código de verificación de UMABot";

    const EMAIL_PLAIN = <<<EOD
    ¡Bienvenido a UMABot-ng!
    Verifica tu cuenta con este código: %d
    Si tienes problemas para verificar tu cuenta puedes consultar la guía de instalación aquí: %s o contactar con la administración aquí: %s
    Este es un mensaje automático, por favor no respondas a este correo.
    EOD;

    const EMAIL_HTML = <<<EOD
    <p>Bienvenido a UMABot-ng!</p>
    <p>Verifica tu cuenta con este código: <b>%d</b><p>
    <p>
        Si tienes problemas para verificar tu cuenta puedes consultar la guía de instalación <a href="%s">aquí</a>
    </p>
    <p>
        También puedes contactar con la administración <a href="%s">aquí</a>
    </p>
    <p>Este es un mensaje automático, por favor no respondas a este correo</p>
    EOD;

    // -- Messages -- //
    const MESSAGE_WAITING_MODERATION = "¡Tu mensaje ha sido agregado a la cola de moderación con éxito! Posición: %d. Se publicará cuando sea aprobado por un moderador";
    
    const MESSAGE_WAITING_PUBLICATION = "Tu mensaje ha sido agregado a la cola para ser publicado! Posición: %d";

    const MESSAGE_ERROR_DB = "Ha habido un error al registrar tu mensaje, por favor inténtalo de nuevo más tarde";

    const MESSAGE_ERROR_NOT_REGISTERED = "No tienes la cuenta verificada para poder enviar mensajes, más información en: %s";

    const MESSAGE_APPROVED = "¡Uno de tus mensajes ha sido aprobado! Has sido agregado a la cola de publicación, posición: %d";

    const MESSAGE_BLOCKED = "Uno de tus mensajes ha sido denegado por la administración!";

    // -- POLL -- //
    const POLL_ERROR_PARAMS = "Parámetros inválidos";

    const POLL_ERROR_MSG = "El cuerpo excede los " . Values::MESSAGE_MAX_CHARS . " caracteres máximos";

    const POLL_ERROR_DURATION = "La duración debe de ser un número, máximo: " .  Values::POLL_MAX_DURATION . '(' . Values::POLL_MAX_DURATION / 60 / 24 . " días)";

    const POLL_ERROR_OPTION = "Una de las opciones excede los " . Values::POLL_MAX_CHARS . " caracteres máximos";

    const POLL_SENT = "Encuesta agregada con éxito, pendiente de moderación: %s";

    // -- Misc -- //
    const MISC_MAINTENANCE = "Bot en mantenimiento, vuelve a intentarlo en unas horas";
}
