<?php $this->layout('layouts/default', ['title' => 'Términos de Uso']) ?>

<?php $this->start('header') ?>
    <p class="title">Términos de Uso</p>
<?php $this->stop() ?>
<div class="container">
    <div class="content">
        <ul>
            <li>
                Tanto la cuenta de Twitter <a href="https://twitter.com/<?=$this->botName()?>" target="_blank"> @<?=$this->botName()?></a>
                como esta página web <b>no está afiliada a la Universidad de Málaga.</b></li>
            <li>Al enviar mensajes a través de este servicio asumes la responsabilidad del contenido del mensaje.</li>
            <li>
                Los mensajes son publicados de forma anónima,
                internamente tanto el NIU (UMA) como el ID del usuario (Twitter) están disponibles para ser consultados si es necesario.</li>
            <li>
                Todas las reglas de Twitter son también las reglas de este servicio.
                Por lo tanto, cualquier tipo de propagación de información personal, amenazas... no son permitidas.
            </li>
            <li>
                La información personal almancenada localmente (NIU e ID de usuario de Twitter) sólo son
                usadas para el funcionamiento del servicio y en ningún momento es consultada por personas ajenas al proyecto.
            </li>
            <li>
                La moderación de este servicio se reserva el derecho de expulsar usuarios debido a un uso indebido de las funciones del programa
            </li>
        </ul>
    </div>
    <div class="content">
        <p>Al usar el servicio confirmas haber leído y aceptado los términos de uso.</p>
        <p>Si quieres eliminar tu cuenta contacta con el administrador de la página <a href="<?=$this->contact()?>">aquí</a></p>
    </div>
</div>
