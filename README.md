# NFSe Padrão Nacional

Pacote para geração de NFSe Padrão Nacional (https://www.nfse.gov.br/) usando componentes NFePHP (https://github.com/nfephp-org).

Este pacote foi desenvolvido para atender algumas das minhas necessidades, implementei o que utilizei e a toque de caixa. Se quiser colaborar envie seu PR.

**Em desenvolvimento. Use por sua conta e risco.**

## Install

**Este pacote é desenvolvido para uso do [Composer](https://getcomposer.org/), então não terá nenhuma explicação de instalação alternativa.**

```bash
composer require hadder/nfse-nacional
```


### Serviços implementados
- consultarNfseChave
- consultarDpsChave
- consultarNfseEventos
- consultarDanfse
- enviaDps
- cancelaNfse

## Requerimentos
- PHP 8.2+
- ext-zlib
- ext-openssl
- ext-dom
- ext-curl

