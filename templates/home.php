<?php $this->layout('layouts/hero', ['title' => 'Inicio']) ?>

<div class="content">
    <p class="title">UMABot-ng</p>
    <div class="buttons is-centered is-responsive">
        <?php foreach($this->links() as $link): ?>
        <a class="button <?=$this->e($link['color'])?>" href="<?=$this->url($link['endpoint'])?>"><?=$this->e($link['name'])?></a>
        <?php endforeach ?>
    </div>
    <div class="columns is-centered is-vcentered is-multiline">
        <div class="column is-half">
            <div class="box">
                <p><b>Estadísticas</b></p>
                <p>Mensajes pendientes de moderar: <?=$this->e($stats->moderation)?></p>
                <p>Mensajes pendientes de publicar: <?=$this->e($stats->content)?></p>
                <p>Mensajes recibidos en total: <?=$this->e($stats->total)?></p>
            </div>
        </div>
        <div class="column is-half">
            <div class="box">
                <p><b>Instancia</b></p>
                <p>Moderación manual: <i><?=$instance->moderation ? 'Sí' : 'No'?></i></p>
                <p>Verificación de identidad: <i><?=$instance->verification ? 'Sí' : 'No'?></i></p>
            </div>
        </div>
    </div>
</div>
