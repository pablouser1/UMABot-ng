<div class="buttons is-centered">
    <a class="button is-success" href="<?=$this->url('/admin/approve', ['id' => $id])?>">Aprobar</a>
    <a class="button is-warning" href="<?=$this->url('/admin/delete', ['id' => $id])?>">Eliminar</a>
    <a class="button is-danger" href="<?=$this->url('/admin/block', ['id' => $id])?>">Denegar</a>
</div>
