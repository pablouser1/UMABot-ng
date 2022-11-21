<?php $this->layout('layouts/default', ['title' => 'Dashboard']) ?>

<?php $this->start('header') ?>
    <p class="title">Dashboard</p>
<?php $this->stop() ?>
<div class="container">
    <?=$this->insert('components/filter')?>
    <hr />
    <?php if (empty($contents)): ?>
    <p>No hay mensajes</p>
    <?php endif ?>
    <div class="columns is-centered is-vcentered is-multiline">
        <?php foreach($contents as $content): ?>
            <div class="column is-one-third">
                <div class="box">
                    <div class="content">
                        <p class="has-text-centered">Item <?=$this->e($content->id)?></p>
                        <p><?=$this->e($content->msg)?></p>
                        <?php if ($content->attachType === 'photo'): ?>
                            <figure class="image has-text-centered">
                                <img src="<?=$this->url('/stream', ['url' => $content->attachData])?>" />
                            </figure>
                        <?php endif ?>
                        <?php if ($content->attachType === 'video'): ?>
                            <video class="has-text-centered" preload="metadata" controls>
                                <source src="<?=$this->url('/stream', ['url' => $content->attachData])?>" />
                            </video>
                        <?php endif ?>
                        <?php if ($content->attachType === 'poll'): ?>
                            <?php $poll = explode(';', $content->attachData); $duration = $poll[0]; array_shift($poll) ?>
                            <div class="content">
                                <ul>
                                    <?php foreach($poll as $item): ?>
                                        <li><?=$this->e($item)?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        <?php endif ?>
                        <?php if ($content->approved == 0 && $content->blocked == 0): ?>
                            <?=$this->insert('components/moderation_controls', ['id' => $content->id])?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
