<?php $this->layout('layouts/default', ['title' => 'Dashboard']) ?>

<?php $this->start('header') ?>
    <p class="title">Dashboard</p>
<?php $this->stop() ?>
<div class="container">
    <div class="columns is-centered">
        <?php foreach($contents as $content): ?>
            <div class="column is-one-quarter">
                <div class="box">
                    <div class="content">
                        <p class="has-text-centered">Item <?=$this->e($content->id)?></p>
                        <p><?=$this->e($content->msg)?></p>
                        <?php if ($content->type === 'photo'): ?>
                            <figure class="image has-text-centered">
                                <img src="<?=$this->url('/stream', ['url' => $content->media_url])?>" />
                            </figure>
                        <?php endif ?>
                        <?php if ($content->type === 'video'): ?>
                            <video class="has-text-centered" preload="metadata" controls>
                                <source src="<?=$this->url('/stream', ['url' => $content->media_url])?>" />
                            </video>
                        <?php endif ?>
                    </div>
                    <div class="buttons is-centered">
                        <a class="button is-success" href="<?=$this->url('/admin/approve', ['id' => $content->id])?>">Aprobar</a>
                        <a class="button is-danger" href="<?=$this->url('/admin/block', ['id' => $content->id])?>">Denegar</a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
