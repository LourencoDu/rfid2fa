<?php

use RFID2FA\Controller\{
  CadastroController,
  HomeController,
  LoginController
};

$url = rtrim(str_replace("rfid2fa/", "", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)), '/');

// Rotas principais
switch ($url) {
  case '':
    (new HomeController())->index();
    break;
  case '/login':
    (new LoginController())->index();
    break;
  case '/cadastro':
    (new CadastroController())->index();
    break;
  case '/logout':
    LoginController::logout();
    break;
  default:
    // Se nenhuma rota for encontrada
    (new HomeController())->index();
    break;
}
