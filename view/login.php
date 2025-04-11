<div class="content">
  <div class="right-side">
    <span>LOGO</span>

    <h2 class="title">Acesse sua conta</h2>

    <?php if (!empty($data['erro'])): ?>
        <p style="color:red"><?= $data['erro'] ?></p>
    <?php endif; ?>

    <form class="w-100" method="POST" action="<?= BASE_URL ?>/login/autenticar">
      <div class="form-control w-100">
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" placeholder="seuemail@exemplo.com">
      </div>
  
      <div class="form-control w-100">
        <label for="senha">Senha</label>
        <input type="password" name="senha" id="senha" placeholder="">
      </div>
  
      <button class="w-100" type="submit">Entrar</button>
    </form>
  </div>
</div>