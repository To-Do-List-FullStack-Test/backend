# Application de Gestion de Tâches (Todo List)

Cette application full stack permet de gérer des tâches personnelles avec un système d'authentification, de notifications et de gestion des tâches en temps réel.

## Architecture du Projet

Le projet est divisé en deux parties principales :

### Backend (Laravel)

- **Framework** : Laravel 12
- **Base de données** : MySql
- **Authentification** : JWT (JSON Web Token)
- **Temps réel** : Pusher pour les notifications

### Frontend (Vue.js)

- **Framework** : Vue.js 3 avec Composition API
- **Gestion d'état** : Pinia
- **Routage** : Vue Router
- **HTTP Client** : Axios
- **Temps réel** : Laravel Echo avec Pusher

## Fonctionnalités

- **Authentification** : Inscription, connexion et déconnexion
- **Gestion de profil** : Affichage et modification des informations utilisateur
- **Gestion des tâches** :
  - Création, lecture, mise à jour et suppression de tâches
  - Filtrage par statut et priorité
  - Recherche de tâches
  - Marquage des tâches comme terminées
- **Notifications** : Notifications en temps réel pour les actions importantes

## Prérequis

- PHP 8.2 ou supérieur
- Composer
- Node.js (version 20.19.0 ou 22.12.0 ou supérieur)
- npm ou yarn

## Installation et Configuration

### 1. Cloner le dépôt

```bash
git clone <url-du-depot>
cd toDoList
```

### 2. Configuration du Backend

```bash
cd todo-backend

# Installation des dépendances
composer install

# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Générer la clé JWT
php artisan jwt:secret



# Exécuter les migrations
php artisan migrate

# Optionnel : Ajouter des données de test
php artisan db:seed
```

### 3. Configuration du Frontend

```bash
cd ../todo-frontend

# Installation des dépendances
npm install

# Vérifier que le fichier .env pointe vers l'API backend
# VITE_API_URL=http://localhost:8000/api
```

## Démarrage de l'Application

### 1. Démarrer le Backend

```bash
cd todo-backend
php artisan serve
```

Le serveur backend sera accessible à l'adresse : http://localhost:8000

### 2. Démarrer le Frontend

```bash
cd todo-frontend
npm run dev
```

L'application frontend sera accessible à l'adresse : http://localhost:5173

## Tests

### Tests Backend

```bash
cd todo-backend
php artisan test
```

## Structure des API

### Authentification

- `POST /api/auth/register` - Inscription d'un nouvel utilisateur
- `POST /api/auth/login` - Connexion d'un utilisateur
- `POST /api/logout` - Déconnexion (nécessite authentification)

### Profil Utilisateur

- `GET /api/profile` - Obtenir le profil de l'utilisateur connecté
- `PUT /api/profile` - Mettre à jour le profil de l'utilisateur

### Tâches

- `GET /api/tasks` - Liste des tâches de l'utilisateur
- `POST /api/tasks` - Créer une nouvelle tâche
- `GET /api/tasks/{id}` - Détails d'une tâche spécifique
- `PUT /api/tasks/{id}` - Mettre à jour une tâche
- `DELETE /api/tasks/{id}` - Supprimer une tâche
- `PATCH /api/tasks/{id}/complete` - Marquer une tâche comme terminée

### Notifications

- `GET /api/notifications` - Liste des notifications
- `GET /api/notifications/unread-count` - Nombre de notifications non lues
- `POST /api/notifications/mark-all-read` - Marquer toutes les notifications comme lues
- `PATCH /api/notifications/{id}/read` - Marquer une notification comme lue

## Contribution

Pour contribuer au projet, veuillez suivre les étapes suivantes :

1. Forker le projet
2. Créer une branche pour votre fonctionnalité (`git checkout -b feature/amazing-feature`)
3. Commiter vos changements (`git commit -m 'Add some amazing feature'`)
4. Pousser vers la branche (`git push origin feature/amazing-feature`)
5. Ouvrir une Pull Request

## Licence

Ce projet est sous licence MIT.
