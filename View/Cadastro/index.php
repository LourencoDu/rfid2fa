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
    <div class="flex flex-row h-12 items-center gap-1">
      <i class="fa-solid fa-id-card text-3xl"></i>
      <span class="text-2xl font-semibold">RFID2FA</span>
    </div>

    <h2 class="text-2xl font-semibold">Criar conta</h2>

    <?php include COMPONENTS . "backend-error.php"; ?>

    <form id="form" class="w-full flex flex-col gap-2">
      <div class="form-control flex-col">
        <label for="nome">Nome <span class="text-red-500">*</span></label>
        <input type="text" name="nome" id="nome" data-validate="nome" placeholder="ex.: Sr Joaquim" value="<?= $nome ?>">
        <span class="helper-text danger hidden">O nome deve conter pelo menos 2 caracteres.</span>
      </div>

      <div class="form-control flex-col">
        <label for="email">E-mail <span class="text-red-500">*</span></label>
        <input type="email" name="email" id="email" data-validate="email" placeholder="seuemail@exemplo.com" value="<?= $email ?>">
        <span class="helper-text danger hidden">Digite um e-mail válido.</span>
      </div>

      <div class="form-control flex-col">
        <label for="senha">Senha <span class="text-red-500">*</span></label>
        <input type="password" name="senha" id="senha" data-validate="senha" placeholder="" value="<?= $senha ?>">
        <span class="helper-text danger hidden">A senha deve conter pelo menos 8 caracteres, incluindo letras maiúsculas, minúsculas, números e símbolos.</span>
      </div>

      <button class="button mt-4 mb-8" type="submit">Cadastrar</button>

            <div class="flex flex-row justify-center items-center px-1.5 pt-8 gap-2 border-t border-t-gray-400">
        <span class="">Já tem uma conta?</span>
        <a href="login" class="hoverable font-semibold">Entre na plataforma</a>
      </div>
    </form>
  </div>
</div>