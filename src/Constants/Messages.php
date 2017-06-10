<?php

namespace Scaville\Lemon\Constants;

interface Messages {
    const DB_CONNECTION_PARAMS_NULL = "As informações necessárias para conexão não foram informadas!";
    const DB_QUERY_DELETE_RESTRICT = "Não foi encontrado um comando DELETE na query informada!";
    const DB_QUERY_SELECT_RESTRICT = "Não foi encontrado um comando SELECT na query informada!";
    const CORE_APP_SINGLETON_RESTRICT = "Não é possível instanciar esta classe!";
    const EXTENSION_NOT_ALLOWED = "Não é permitido o acesso direto a arquivos!";
    const VW_MODULE_UNDEFINED = "Não foi definido o módulo da view!";
}
