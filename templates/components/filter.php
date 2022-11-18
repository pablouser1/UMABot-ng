<?php $mode = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : 'waiting'; ?>
<form action="<?=$this->url('/admin')?>" method="GET">
    <div class="field is-grouped is-grouped-centered">
        <div class="control">
            <div class="select">
                <select name="type">
                    <option value="waiting" <?=$mode === 'waiting' ? 'selected' : ''?>>Waiting</option>
                    <option value="approved" <?=$mode === 'approved' ? 'selected' : ''?>>Approved</option>
                    <option value="blocked" <?=$mode === 'blocked' ? 'selected' : ''?>>Blocked</option>
                    <option value="all" <?=$mode === 'all' ? 'selected' : ''?>>All</option>
                </select>
            </div>
        </div>
        <div class="control">
            <button class="button" type="submit">Ir</button>
        </div>
    </div>
</form>
