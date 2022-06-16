<p align="center">
  <img src="http://www.ciawn.com.br/images/logo.png" width="200" alt="Front-end Brasil">
</p>
<br>

## Contexto

Esse serviço serve como um adaptador para as integrações com as plataformas de delivery.

## Dependencias

 - Docker
 - Docker Compose

## Subindo

 - `./vendor/bin/sail up -d`
 - `./vendor/bin/sail artisan migrate`

## Rodando os testes

 - `./vendor/bin/sail up -d`
 - `cp .env .env.testing`
 - `./vendor/bin/sail artisan migrate --env=testing`
 - `./vendor/bin/sail test`

## Deploy

TODO

## Contribuindo

Pode contribuir abrindo sugestoes de melhorias através de issues ou PRs.