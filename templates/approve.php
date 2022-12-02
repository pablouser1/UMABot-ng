<?php $this->layout('layouts/hero', ['title' => 'Aprobar mensaje']) ?>

<div class="content">
    <p class="title">Aprobar mensaje: <?=$this->id?></p>
    <div class="box">
        <form action="<?=$this->url('/admin/approve', ['id' => $id])?>" method="POST">
            <div class="field">
                <label class="label">Comentario</label>
                <div class="control">
                    <textarea name="note" class="textarea"></textarea>
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
