<?php $this->layout('layouts/hero', ['title' => 'Inicio']) ?>

<div class="content">
    <p class="title">UMABot-ng</p>
    <div class="buttons is-centered is-responsive">
        <?php foreach($this->links() as $link): ?>
        <a class="button <?=$this->e($link['color'])?>" href="<?=$this->url($link['endpoint'])?>"><?=$this->e($link['name'])?></a>
        <?php endforeach ?>
    </div>
    <p>Mensajes pendientes de moderar: <?=$this->e($stats->moderation)?></p>
    <p>Mensajes pendientes de publicar: <?=$this->e($stats->content)?></p>
    <p>Mensajes recibidos en total: <?=$this->e($stats->total)?></p>
</div>
