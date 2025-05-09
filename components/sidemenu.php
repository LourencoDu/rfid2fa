<?php
$menuItens = [
  ['rota' => 'prestador', 'icone' => 'fa-screwdriver-wrench'],
  ['rota' => 'usuario', 'icone' => 'fa-user'],
  ['rota' => 'veiculo', 'icone' => 'fa-car'],
  ['rota' => 'funcionario', 'icone' => 'fa-user-group'],
  ['rota' => 'servico', 'icone' => 'fa-gear'],
  ['rota' => 'prestador/proximos', 'icone' => 'fa-map-location-dot'],
];

function isActiveRoute($rotaItem)
{
  $rotaAtual = $_SERVER['REQUEST_URI']; // Obtém a URL atual  
  return strpos($rotaAtual, $rotaItem) !== false ? "true" : "false"; // Verifica se a rota do item está na URL atual
}
?>

<div class="flex flex-col flex-1 max-w-12 items-center gap-4">
  <div class="flex flex-1 w-full flex-col justify-start items-center border-y border-y-gray-700/40 py-4 gap-2">
    <?php foreach ($menuItens as $item): ?>
      <a aria-selected="<?= isActiveRoute($item['rota']); ?>" href="/<?= BASE_DIR_NAME ?>/<?= $item['rota'] ?>" class="flex h-12 w-12 border rounded-lg border-gray-700/20 justify-center items-center hover:border-primary/60 hover:text-primary transition duration-300 cursor-pointer  aria-selected:bg-white aria-selected:text-primary aria-selected:border-primary/60">
        <i class="fa-solid <?= $item['icone'] ?>"></i>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Botão de logout -->
  <div class="flex flex-col justify-end items-center">
    <a href="/<?= BASE_DIR_NAME ?>/logout" class="flex h-12 w-12 border rounded-lg border-gray-700/20 justify-center items-center hover:border-primary/60 hover:text-primary transition duration-300 cursor-pointer">
      <i class="fa-solid fa-arrow-right-from-bracket"></i>
    </a>
  </div>
</div>