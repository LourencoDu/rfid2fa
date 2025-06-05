<?php

use RFID2FA\Helper\Util;

$leituras = array();
if (isset($data["leituras"])) {
  $leituras = $data["leituras"];
}
$quantidade = count($leituras);
?>

<div class="flex flex-1 flex-col border border-gray-300 rounded-xl justify-between">
  <div class="flex flex-row items-center justify-between h-14 px-5 border-b border-gray-300">
    <span class="text-lg font-semibold">Leituras (<?= $quantidade ?>)</span>
  </div>
  <div class="flex flex-1 justify-center">
    <div class="w-full overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-300">
        <thead class="bg-gray-500/10">
          <tr>
            <th scope="col" class="px-5 py-2 font-normal text-center w-[60px] border-r border-gray-300">
              ID
            </th>
            <th scope="col" class="px-5 py-2 font-normal text-left border-r border-gray-300">
              UID do cartão lido
            </th>
            <th scope="col" class="px-5 py-2 font-normal text-left border-r border-gray-300">
              Ação realizada
            </th>
            <th scope="col" class="px-5 py-2 font-normal text-left w-[120px] border-r border-gray-300">
              Data da leitura
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-300">
          <?php foreach ($leituras as $index => $leitura) : ?>
            <tr class="hover:bg-gray-500/5">
              <td class="px-5 py-2 whitespace-nowrap text-sm text-center border-r border-gray-300"><?= $leitura->id ?></td>
              <td class="px-5 py-2 whitespace-nowrap text-sm border-r border-gray-300"><?= e($leitura->uid_cartao) ?></td>
              <td class="px-5 py-2 whitespace-nowrap text-sm border-r border-gray-300"><?= e($leitura->acao ?? "-") ?></td>
              <td class="px-5 py-2 whitespace-nowrap text-sm text-left border-r border-gray-300"><?= Util::formatarDataHora(e($leitura->data)) ?></td>
            </tr>
          <?php endforeach; ?>

        </tbody>
      </table>

      <?php if ($quantidade == 0) : ?>
        <div class="flex flex-col items-center justify-center py-20">
          <i class="text-primary text-6xl fa-solid fa-flag mb-4"></i>
          <span class="text-lg font-semibold mb-4 text-center">Nenhuma leitura realizado pelo sistema</span>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>