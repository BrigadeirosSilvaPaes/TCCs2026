<?php

/*
|--------------------------------------------------------------------------
| BOOTSTRAP DO SISTEMA
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Responsável por inicializar as dependências fundamentais
| utilizadas pelo backend da aplicação.
|
| A ordem dos carregamentos é importante.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES GERAIS
|--------------------------------------------------------------------------
|
| Define:
|
| - SITE_NAME;
| - SITE_VERSION;
| - ENVIRONMENT;
| - BASE_URL;
| - timezone;
| - configuração de erros.
|
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/app.php";


/*
|--------------------------------------------------------------------------
| CAMINHOS DO SISTEMA
|--------------------------------------------------------------------------
|
| Define constantes utilizadas para localizar:
|
| - backend;
| - frontend;
| - services;
| - storage;
| - uploads;
| - demais diretórios internos.
|
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/paths.php";


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO DO BANCO
|--------------------------------------------------------------------------
|
| Primeiro carregamos as informações/configurações
| relacionadas ao banco.
|
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/database.php";


/*
|--------------------------------------------------------------------------
| CONEXÃO PDO
|--------------------------------------------------------------------------
|
| Responsável por disponibilizar a conexão utilizada
| pela aplicação.
|
| Durante a auditoria será verificado se database.php
| e conexao.php possuem responsabilidades realmente
| diferentes ou se existe código duplicado.
|
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/conexao.php";


/*
|--------------------------------------------------------------------------
| SESSÃO
|--------------------------------------------------------------------------
|
| Inicializa e configura a sessão da aplicação.
|
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/session.php";


/*
|--------------------------------------------------------------------------
| SEGURANÇA
|--------------------------------------------------------------------------
|
| Configura proteções gerais utilizadas pelo sistema.
|
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/security.php";


/*
|--------------------------------------------------------------------------
| PERMISSÕES
|--------------------------------------------------------------------------
|
| Constantes e regras relacionadas aos tipos
| de acesso e perfis disponíveis.
|
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/permissions.php";


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES DE UPLOAD
|--------------------------------------------------------------------------
|
| Limites e configurações relacionadas ao envio
| e armazenamento de arquivos.
|
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/upload.php";


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES DE E-MAIL
|--------------------------------------------------------------------------
|
| Configurações utilizadas pelo serviço de envio
| de mensagens da aplicação.
|
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/email.php";


/*
|--------------------------------------------------------------------------
| SERVIÇO DE E-MAIL
|--------------------------------------------------------------------------
|
| Mantido no bootstrap nesta etapa para preservar
| compatibilidade com os arquivos atuais.
|
| Durante a auditoria dos serviços verificaremos
| se é realmente necessário carregá-lo globalmente.
|
|--------------------------------------------------------------------------
*/

require_once SERVICE_PATH . "/EmailService.php";