<form action="<?=$this->url('/admin')?>" method="GET">
    <div class="field is-grouped is-grouped-centered">
        <div class="control">
            <div class="select">
                <select name="type">
                    <option value="waiting">Waiting</option>
                    <option value="approved">Approved</option>
                    <option value="blocked">Blocked</option>
                    <option value="all">All</option>
                </select>
            </div>
        </div>
        <div class="control">
            <button class="button" type="submit">Ir</button>
        </div>
    </div>
</form>
