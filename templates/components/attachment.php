<?php if ($type === 'photo'): ?>
    <figure class="image has-text-centered">
        <img loading="lazy" src="<?=$this->url('/stream', ['url' => $data])?>" />
    </figure>
<?php elseif ($type === 'video' || $type === 'animated_gif'): ?>
    <video class="has-text-centered" preload="metadata" controls>
        <source src="<?=$this->url('/stream', ['url' => $data])?>" />
    </video>
<?php elseif ($type === 'poll'): ?>
    <?php $poll = explode(';', $data); $duration = $poll[0]; array_shift($poll) ?>
    <div class="content">
        <ul>
            <?php foreach($poll as $item): ?>
                <li><?=$this->e($item)?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
