# Backend - WR602D

Ce backend Symfony gère l'API du jeu de roulette, la gestion des utilisateurs, les scores et les parties jouées.

## 🔧 Installation

```bash
git clone https://github.com/YanisKujo/Back-WR602D.git
cd Back-WR602D
composer install
cp .env .env.local
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## 🚀 Lancer container

```bash
docker-compose up --build
```

## 🔐 Authentification

JWT via LexikJWTAuthenticationBundle

- **Register** : `POST /api/users`
- **Login** : `POST /api/login`

## 📚 Endpoints principaux

- `POST /api/play` : Lancer une partie
- `GET /custom-api/games/highscore` : Meilleur score global

## 📦 Technologies utilisées

- Symfony 6+
- API Platform
- JWT Auth
- Doctrine ORM
