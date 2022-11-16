<?php $this->layout('layouts/default', ['title' => 'Cómo usar']) ?>

<?php $this->start('header') ?>
    <p class="title">Cómo usar</p>
<?php $this->stop() ?>
<div class="container">
    <article class="message is-warning">
        <div class="message-header">
            <p>Antes de empezar</p>
        </div>
        <div class="message-body">
            <div class="content">
                <p>Asegúrate de:</p>
                <ul>
                    <li><a href="<?=$this->url('/terms')?>">Leer</a> los términos de uso</li>
                    <li>Activar tu correo de la UMA</li>
                </ul>
            </div>
        </div>
    </article>
    <div class="content">
        <p class="title">¿Cuál es mi NIU?</p>
        <p>Para poder usar este bot es necesario que sepas tu <b>NIU</b></p>
        <p>Abre la aplicación de la UMA y dirigete a "Servicios". Una vez ahí haz click en "Carnet de Estudiante"</p>
        <p>Debes poder encontrar tu NIU con esta estructura:</p>
        <p>NIU: <b>1234567890</b></p>
    </div>
    <div class="content">
        <p class="title">Solicitar mi PIN</p>
        <p>Una vez conseguido tu NIU abre mensaje directo con el bot y escribe:</p>
        <p><code>/verify TU_NIU</code></p>
        <p>Reemplazando TU_NIU por tu secuencia de números</p>
    </div>
    <div class="content">
        <p class="title">Verificar mi cuenta</p>
        <p>Si el NIU escrito es válido y tienes el correo de la UMA operativo, deberías haber recibido un correo electrónico con un PIN aleatorio</p>
        <p>Con ese pin escribe por mensaje directo al bot:</p>
        <p><code>/pin TU_PIN</code></p>
        <p>Reemplazando TU_PIN por el que recibiste por el correo</p>
    </div>
    <div class="content">
        <p class="title">¡Todo listo!</p>
        <p>Ya puedes usar el bot normalmente</p>
        <p>¡Diviértete!</p>
    </div>
</div>
