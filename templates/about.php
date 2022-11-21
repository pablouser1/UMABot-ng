<?php $this->layout('layouts/default', ['title' => 'Información']) ?>

<?php $this->start('header') ?>
    <p class="title">Información</p>
<?php $this->stop() ?>
<div class="container">
    <div class="content">
        <p>¡Hola! Bienvenido a UMABot-ng</p>
        <p>Este proyecto es un intento de mejorar los bots anterior</a> con más funciones de moderación.</p>
    </div>
    <div class="content">
        <p class="title">Comandos</p>
        <p>- <strong>/verify, /pin</strong></p>
        <p>Utilizados para gestionar la verificación de identidad, haz click <a href="<?=$this->url('/verify')?>">aquí</a> para saber más</p>
        <p>- <strong>/poll</strong></p>
        <p>Utilizado para crear encuestas</p>
        <p>Estructura: <code>/poll "MENSAJE" DURACION "OPCION1" "OPCION2" "OPCION3" "OPCION4"</code></p>
        <p>Parámetros:</p>
        <ul>
            <li>MENSAJE: Texto que aperecerá en la parte superior de la encuesta</li>
            <li>DURACION: Tiempo en minutos en los que los usuarios pueden votar (Ejemplo, para que la encuesta dure 1 día usaríamos 1440</li>
            <li>OPCION1, OPCION2, OPCION3, OPCION4: Opciones en la encuesta, mínimo 2 opciones y máximo 4. Máximo de caracteres por cada opción: 40</li>
        </ul>
            <article class="message is-warning">
            <div class="message-header">
                <p>IMPORTANTE</p>
            </div>
            <div class="message-body">
                <p>Si alguno de los elementos tiene un espacio <b>tienes que entrecomillarlo (usando "")</b></p>
                <p><span class="has-text-success">Bien</span>: <code>/poll "Este es un ejemplo de encuesta" 1440 "Opción 1" "Opción 2" "Opción 3" "Opción 4"</code></p>
                <p><span class="has-text-danger">Mal</span>: <code>/poll Esta encuesta está mal hecha 1440 Opción 1 Opción 2</code></p>
                <p><span class="has-text-success">Bien</span>: <code>/poll Válido 1440 Sí También Obviamente</code></p>
            </div>
        </article>
    </div>
    <div class="content">
        <p class="title">Preguntas frecuentes</p>
        <p>- <strong>¿Por qué tengo que poner mi NIU para usar este bot?</strong></p>
        <p>
            El NIU se usa <b>exclusivamente</b> para verificar que realmente formas parte de la UMA.
            Con esto se filtra una gran cantidad de mensajes y hace que el UMA dentro de UMABot tenga algo más sentido.
        </p>
        <p>- <strong>¿Cuándo se publicará mi mensaje?</strong></p>
        <p>
            Cuando envías un mensaje al bot un Viernes tiene que ser aprobado por un moderador, una vez aprobado tu mensaje estarás en la cola de publicación.
            Si no Viernes acabas en la cola de publicación directamente. Automáticamente cada ~10min se publica un mensaje por orden de entrada.
        </p>
        <p>- <strong>¿Por qué mi mensaje no ha sido aprobado?</strong></p>
        <p>
            Lo más probable es que no cumpla los <a href="<?=$this->url('/terms')?>">términos de uso</a> del bot. Si piensas que ha sido un error puedes contactar con el dueño
            <a href="<?=$this->contact()?>">aquí</a>
        </p>
    </div>
    <div class="content">
        <p class="title">Donaciones</p>
        <p>Mantener este proyecto vivo requiere tanto de dinero como de tiempo. Donar podría ayudarme a mantener este servicio para todos.</p>
        <p>Cualquier donación será bienvenida, puedes donar usando:</p>
        <div class="buttons is-centered">
            <a class="button is-info" href="https://paypal.me/pablouser1" target="_blank">PayPal</a>
            <a class="button is-warning" href="https://liberapay.com/pablouser1" target="_blank">Liberapay</a>
        </div>
        <p>¡Gracias por el apoyo! <span style="color: #e25555;">&#9829;</span></p>
        <p>- Pablo Ferreiro, desarrollador de UMABot-ng</p>
    </div>
    <div class="content">
        <p class="title">Créditos</p>
        <p>Este bot no sería posible sin la ayuda de los siguientes proyectos de código abierto:</p>
        <ul>
            <li><a href="https://github.com/abraham/twitteroauth" rel="nofollow">abraham/twitteroauth</a></li>
            <li><a href="https://github.com/thephpleague/plates" rel="nofollow">Plates</a></li>
            <li><a href="https://github.com/PHPMailer/PHPMailer" rel="nofollow">PHPMailer</a></li>
            <li><a href="https://github.com/bramus/router" rel="nofollow">bramus/router</a></li>
            <li><a href="https://github.com/josegonzalez/php-dotenv" rel="nofollow">josegonzalez/dotenv</a></li>
        </ul>
    </div>
</div>
