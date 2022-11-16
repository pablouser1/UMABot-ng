<?php $this->layout('layouts/hero', ['title' => 'Login']) ?>

<div class="columns is-centered">
  <div class="column is-5-tablet is-4-desktop is-3-widescreen">
    <form action="/admin/login" method="POST">
      <div class="field">
        <label class="label">Username</label>
        <div class="control">
          <input name="username" type="text" class="input" required>
        </div>
      </div>
      <div class="field">
        <label class="label">Password</label>
        <div class="control">
          <input name="password" type="password" placeholder="*******" class="input" required>
        </div>
      </div>
      <div class="field">
        <button type="submit" class="button is-success">Login</button>
      </div>
    </form>
  </div>
</div>
