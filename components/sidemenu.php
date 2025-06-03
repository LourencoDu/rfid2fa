<?php
$usuario = $_SESSION["usuario"];

$menuItens = [
  ['rota' => 'home', 'icone' => 'fa-home', 'label' => "Início"],
];

function isActiveRoute($rotaItem)
{
  $rotaAtual = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

  // Nome da pasta base do projeto (ajuste conforme necessário)
  $basePath = '/rfid2fa'; // Substitua por sua pasta se for diferente

  // Remove o basePath da URL atual
  if (strpos($rotaAtual, $basePath) === 0) {
    $rotaAtual = substr($rotaAtual, strlen($basePath));
  }

  $rotaAtual = trim($rotaAtual, '/');

  if ($rotaItem === 'home') {
    return ($rotaAtual === '' || $rotaAtual === 'home') ? "true" : "false";
  }

  return strpos($rotaAtual, $rotaItem) !== false ? "true" : "false";
}
?>
<div id="sidemenu" class="z-10">
  <div id="sidemenu-backdrop" class="fixed z-39 hidden bg-black/30 top-0 left-0 w-full h-full backdrop-blur-xs"></div>
  <div id="sidemenu-content" class="fixed flex hidden sm:static top-0 left-0 z-40 h-full w-20 sm:max-w-12 px-2 py-4 sm:p-0 bg-gray-100 sm:flex flex-col sm:flex-1 items-center gap-4 transform transition-transform duration-150 ease-in-out -translate-x-full sm:translate-x-0 sm:transition-none">
    <a href="/<?= BASE_DIR_NAME ?>/home" class="hidden sm:flex flex-row h-10 w-12 items-center justify-center gap-1 hover:text-primary transition">
      <i class="fa-solid fa-id-card text-3xl"></i>
    </a>

    <div class="flex flex-1 w-full flex-col justify-start items-center border-b sm:border-y border-gray-700/40 pb-4 sm:pt-4 gap-2">
      <?php foreach ($menuItens as $item): ?>
        <div class="relative group">
          <a
            aria-selected="<?= isActiveRoute($item['rota']); ?>"
            href="/<?= BASE_DIR_NAME ?>/<?= $item['rota'] ?>"
            class="sidemenu item">
            <i class="fa-solid <?= $item['icone'] ?>"></i>
          </a>
          <div class="absolute left-full ml-2 top-1/2 -translate-y-1/2 whitespace-nowrap px-2 py-1 bg-black text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity z-10">
            <?= $item['label'] ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php include COMPONENTS . "user-menu.php"; ?>
  </div>
</div>