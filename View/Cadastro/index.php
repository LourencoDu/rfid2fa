<?php

$nome = "";
$email = "";
$senha = "";

if (isset($data["form"])) {
  $nome = $data["form"]["nome"] ?? "";
  $email = $data["form"]["email"] ?? "";
  $senha = $data["form"]["senha"] ?? "";
}

?>

<div class="flex flex-row flex-1 py-12 px-4 lg:gap-25 lg:px-25 xl:gap-50 xl:px-50 justify-center">
  <div class="flex flex-col flex-1 max-w-120 py-12 px-6 gap-8">
    <span>RFID2FA</span>

    <h2 class="text-2xl font-semibold">Criar uma conta</h2>

    <?php if (!empty($data['erro'])): ?>
      <p style="color:red"><?= $data['erro'] ?></p>
    <?php endif; ?>

    <form id="form" class="w-full flex flex-col gap-10" method="POST" action="cadastro">
      <div id="step-2-usuario" class="step w-full flex flex-col gap-2">
        <div class="form-control">
          <label for="nome">Nome <span class="text-red-500">*</span></label>
          <input type="text" name="nome" id="nome" value="<?= $nome ?>">
        </div>

        <div class="form-control">
          <label for="email">E-mail <span class="text-red-500">*</span></label>
          <input type="email" name="email" id="email" placeholder="seuemail@exemplo.com" value="<?= $email ?>">
        </div>

        <div class="w-full">
          <div class="form-control">
            <label for="senha">Senha <span class="text-red-500">*</span></label>
            <input type="password" name="senha" id="new-senha" placeholder="" value="<?= $senha ?>">
          </div>
        </div>

        <button id="step2-submit-button" type="button" class="button mt-6" onclick="submitForm()">Confirmar</button>
      </div>

      <div class="flex flex-row justify-center items-center px-1.5 pt-8 gap-2 border-t border-t-gray-400">
        <span class="">Já tem uma conta?</span>
        <a href="login" class="hoverable font-semibold">Entre na plataforma</a>
      </div>
    </form>
  </div>
</div>