<?php

use RFID2FA\Controller\{
  UsuarioController,
  LeituraController,
  MeuPerfilController
};

$url = rtrim(str_replace("rfid2fa/", "", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)), '/');

// Rotas principais
switch ($url) {
  case '':
  case '/home':
    (new MeuPerfilController())->index();
    break;
  case '/login':
    (new UsuarioController())->login();
    break;
  case '/api/login':
    (new UsuarioController())->logarAPI();
  break;
  case '/cadastro':
    (new UsuarioController())->cadastro();
    break;
  case '/cadastro/bem-vindo':
    (new UsuarioController())->bemVindo();
    break;
  case '/api/cadastro':
    (new UsuarioController())->cadastrarAPI();
  break;
  case '/api/leitura/cadastro':
    (new LeituraController())->cadastrar();
  break;
  case '/logout':
    UsuarioController::logout();
    break;
  case '/meu-perfil':
    (new MeuPerfilController())->index();
    break;
  case '/leitura':
    (new LeituraController())->index();
    break;
  case '/api/usuario/alterar-senha':
    (new UsuarioController())->alterarSenhaAPI();
    break;
  case '/api/usuario/alterar-cartao':
    (new UsuarioController())->alterarCartaoAPI();
    break;
  default:
    // Se nenhuma rota for encontrada
    (new MeuPerfilController())->index();
    break;
}
