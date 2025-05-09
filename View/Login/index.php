<?php
$email = "";
$senha = "";
if (isset($data["form"])) {
  $email = $data["form"]["email"] ?? "";
  $senha = $data["form"]["senha"] ?? "";
}
?>

<div class="flex flex-row flex-1 py-12 px-4 lg:gap-25 lg:px-25 xl:gap-50 xl:px-50 justify-center">
  <div class="flex flex-col flex-1 max-w-120 py-12 px-6 gap-8">
    <div class="flex flex-row h-12 items-center gap-2">
      <i class="fa-solid fa-key text-3xl"></i>
      <span class="text-2xl font-semibold">RFID 2FA</span>
    </div>

    <h2 class="text-2xl font-semibold">Acesse sua conta</h2>

    <?php if (!empty($data['erro'])): ?>
      <p style="color:red"><?= $data['erro'] ?></p>
    <?php endif; ?>

    <form class="w-full flex flex-col gap-2" method="POST" action="login">
      <div class="form-control">
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" placeholder="seuemail@exemplo.com" value="<?= $email ?>">
      </div>

      <div  class="form-control">
        <label for="senha">Senha</label>
        <input type="password" name="senha" id="senha" placeholder="" value="<?= $senha ?>">
      </div>

      <button class="button mt-4 mb-8" type="submit">Entrar</button>

      <div class="flex flex-row justify-center items-center px-1.5 pt-8 gap-2 border-t border-t-gray-400">
        <span class="">Não tem uma conta?</span>
        <a href="cadastro" class="hoverable font-semibold">Criar uma conta</a>
      </div>
    </form>
  </div>
</div>