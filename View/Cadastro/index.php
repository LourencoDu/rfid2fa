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

<div class="hidden z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
  <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
      <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="flex flex-col items-center gap-2">
            <div class="mx-auto flex size-20 shrink-0 items-center justify-center rounded-full bg-gray-200">
              <i class="fa-solid fa-address-card text-primary text-4xl"></i>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-base font-semibold text-gray-900" id="modal-title">Aguardando a leitura do cartão...</h3>
              <div class="pt-2">
                <p class="text-sm text-gray-500">Aproxime o seu cartão de acesso no dispositivo de leitura ESP32 para finalizar o seu cadastro no sistema.</p>
              </div>
            </div>
          </div>
        </div>
        <div class=" bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
          <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</div>