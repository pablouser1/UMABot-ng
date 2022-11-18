<?php $this->layout('layouts/hero', ['title' => 'Bloquear mensaje']) ?>

<div class="content">
    <p class="title">Bloquear mensaje: <?=$this->id?></p>
    <div class="box">
        <form action="<?=$this->url('/admin/block', ['id' => $id])?>" method="POST">
            <div class="field">
                <label class="label">Motivo</label>
                <div class="control">
                    <textarea name="reason" class="textarea"></textarea>
                </div>
            </div>
            <div class="field">
                <div class="control">
                    <button class="button is-success" type="submit">Enviar</button>
                </div>
            </div>
        </form>
    </div>
</div>
