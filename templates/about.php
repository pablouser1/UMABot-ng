<?php $this->layout('layouts/default', ['title' => 'Acerca de / FAQ']) ?>

<?php $this->start('header') ?>
    <p class="title">Acerca de / FAQ</p>
<?php $this->stop() ?>
<div class="container">
    <div class="content">
        <p>¡Hola! Bienvenido a UMABot-ng.</p>
        <p>Este proyecto es un intento de mejorar el ya detenido <a href="https://twitter.com/bot_UMA_">bot anterior</a> con más funciones de moderación.</p>
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
</div>
