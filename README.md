# OGCS

Application web Symfony `7.4` de gestion de club sportif.

Le projet couvre notamment la gestion des joueurs, équipes, notes, absences et inventaire, avec prise en charge de l’upload d’images des joueurs via `VichUploaderBundle`.

## Sommaire

- [Stack technique](#stack-technique)
- [Prérequis](#prérequis)
- [Démarrage rapide](#démarrage-rapide)
- [Architecture des services Docker](#architecture-des-services-docker)
- [Configuration](#configuration)
- [Workflow de développement](#workflow-de-développement)
- [Upload d’images joueurs](#upload-dimages-joueurs)
- [Dépannage](#dépannage)
- [Arrêt des services](#arrêt-des-services)

## Stack technique

- `PHP >= 8.2`
- `Symfony 7.4`
- `Doctrine ORM` + `Doctrine Migrations`
- `MySQL 8`
- `Twig` + `AssetMapper`
- `VichUploaderBundle` + `imagine/imagine` (driver `GD`)
- `Docker` / `Docker Compose`

## Prérequis

- Docker
- Docker Compose (plugin `docker compose`)

## Démarrage rapide

Depuis la racine du projet :

```bash
docker compose up -d --build
```

Puis appliquer les migrations :

```bash
docker compose exec -T php php bin/console doctrine:migrations:migrate --no-interaction
```

Accès principaux :

- Application : [http://localhost:8005](http://localhost:8005)
- phpMyAdmin : [http://localhost:8081](http://localhost:8081) (ou le port défini via `PHPMYADMIN_PORT`)
- MySQL (depuis l’hôte) : `127.0.0.1:3308`

## Architecture des services Docker

Le fichier `compose.yaml` définit les services suivants :

- `php` :
  - build depuis `docker/php/Dockerfile`
  - exécution de Symfony via le serveur PHP intégré (`php -S 0.0.0.0:8000 -t public`)
  - mapping de port `8005:8000`
  - montage du code source local dans `/app`
- `database` :
  - image `mysql:8`
  - mapping de port `3308:3306`
  - volume persistant `mysql_data`
- `phpmyadmin` :
  - image `phpmyadmin/phpmyadmin:latest`
  - mapping de port `${PHPMYADMIN_PORT:-8081}:80`

## Configuration

La configuration d’environnement est gérée via `.env` et `.env.local`.

Variables importantes côté Docker :

- `DATABASE_URL` (service `php`) : connexion MySQL utilisée par Symfony
- `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD` (service `database`)
- `PHPMYADMIN_PORT` : personnalise le port local de phpMyAdmin

## Workflow de développement

### Logs

```bash
docker compose logs -f php
docker compose logs -f database
docker compose logs -f phpmyadmin
```

### Commandes Symfony usuelles

```bash
# Vider le cache
docker compose exec -T php php bin/console cache:clear

# Lister les routes
docker compose exec -T php php bin/console debug:router

# Vérifier les extensions PHP disponibles
docker compose exec -T php php -m
docker compose exec -T php php --ri gd
```

### Tests

```bash
docker compose exec -T php php bin/phpunit
```

## Upload d’images joueurs

- Répertoire de stockage : `public/uploads/player-photos`
- Le nom du fichier est stocké en base dans `players.photo_name`
- La chaîne de traitement repose sur `VichUploaderBundle` + redimensionnement `Imagine` (GD)

En cas de problème d’affichage :

1. Vérifier la présence du fichier dans `public/uploads/player-photos`
2. Vérifier la valeur `photo_name` en base
3. Vérifier que l’URL du fichier renvoie un `Content-Type: image/*`
4. Contrôler que l’extension `gd` est bien active dans le conteneur PHP

## Dépannage

### Rebuild complet

```bash
docker compose down
docker compose up -d --build
```

### Repartir avec une base vide

```bash
docker compose down -v
docker compose up -d --build
docker compose exec -T php php bin/console doctrine:migrations:migrate --no-interaction
```

## Arrêt des services

```bash
docker compose down
```

