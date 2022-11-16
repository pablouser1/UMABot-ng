<?php $this->layout('layouts/hero', ['title' => 'Inicio']) ?>

<p class="title">UMABot-ng</p>
<div class="buttons is-centered is-responsive">
    <?php foreach($this->links() as $link): ?>
    <a class="button <?=$this->e($link['color'])?>" href="<?=$this->url($link['endpoint'])?>"><?=$this->e($link['name'])?></a>
    <?php endforeach ?>
</div>
