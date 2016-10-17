#MoIP PHP client SDK

Biblioteca para integrar sua aplicação PHP com a API do Moip.

###Dependências

* PHP >= 5.4.0
* guzzlehttp/guzzle ~5.3
* illuminate/support 4.* ou 5.*

###Sobre

A biblioteca foi criada para poder inetgrar a API do Moip em aplicações PHP com suporte para o Moip Assinaturas. Foi inspirada nas seguintes bibliotecas:

* [moip/moip-sdk-php](https://github.com/moip/moip-sdk-php)
* [SOSTheBlack/moip](https://github.com/SOSTheBlack/moip)
* [andersao/moip-assinaturas-php](https://github.com/andersao/moip-assinaturas-php)

###Documentação

* [Documentação Oficial Moip](http://dev.moip.com.br)
* [Documentação Oficial Moip Assinaturas](http://dev.moip.com.br/assinaturas-api/)

##Instalação

Instalar a última versão disponível:

```composer require softpampa/moip-sdk-php```

###Configuração

```
<?php

use Softpampa\Moip;

$moip = new Moip(new MoipBasicAuth($token, $key), Moip::ENDPOINT_SANDBOX);
```
