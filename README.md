# A propos de **frecole**

**frecole** est un outil de gestion d'établissement scolaire, simple, offrant une alternative aux établissements ne pouvant pas acquérir une des nombreuses solutions du marché.

**frecole** permet ces fonctionnalités :

- Gestion des utilisateurs (création/modification/suppression)
- Rôles utilisateur : chaque rôle (enseignant, élève, parent...) donne des droits d'accés à l'application
- Gestion des matières
- Gestion des années scolaires

Pour chaque année scolaire :

- Gestion des classes
- Gestion des affectations (élèves et enseignants)
- Gestion des devoirs
- Gestion des corrections
- Accés aux résultats scolaires (parents, élèves, enseignants)

Le modèle de données est consultable ici : [modèle de données](https://docs.google.com/drawings/d/1EbIsxDt3z9tIoRHQU_xx-jazaEomfl7eew0EOv8sZoE/edit?usp=sharing "Modèle de données de frecole")

**frecole** a été développé en PHP avec le merveilleux framework [laravel](https://laravel.com/)

Une démonstration compléte est disponible ici : [https://school-mpa-mono-8f20b5d7a8b2.herokuapp.com/](https://school-mpa-mono-8f20b5d7a8b2.herokuapp.com/)

# Installation - pré requis

**frecole** nécessite l'utilisation de :

- PHP 8.1
- composer (pour installer les dépendances PHP)
- node (pour installer les dépendances JS)

# Installation

- Cloner le projet ```git clone```

> git clone @todo


- Entrer dans le répertoire d'installation

> cd @todo

- Installer les dépendances PHP

> composer install

- Installer les dépendances JS

> npm install

- Initialiser vos environnements. Copier le fichier .env.example en .env

> COPY .env.example .env

Si vous souhaitez utiliser les tests, initialisez également l'environnement de test
> COPY .env.example .env.testing

# Base de données

## sqlite

Par défaut, **frecole** utilise la base de données SQLITE (fournie avec l'installation de PHP). Le fichier d'environnement *.env* pointe vers cette base de données.

Mais vous pouvez également choisir d'utiliser une autre base de données (voir ci-dessous).

## postgre

@todo

## Initialisation des tables

Exécuter la migration pour créer les tables. La migration alimente également quelques tables :

- les matières
- les civilités
- les pays
- les rôles

Libre à vous par la suite de compléter, modifier ces listes.

> php artisan migrate:fresh

Pour créer quelques données de test (des utilisateurs, des années scolaires, des affectations, des notes...), vous pouvez également lancer la migration en précisant --seed :

> php artisan migrate:fresh --seed

# Lancement du projet

A partir du répertoire d'installation, lancez

> php artisan serve

Ceci lancera l'application sur le port 8000, **frecole** sera alors disponible sur [localhost:8000](http://localhost:8000)

# Lancement du projet en mode développement

Si vous souhaitez développer sur le projet, vous pouver exécuter *vite* en parallèle :

> npm run dev

Ceci inspectera en temps réel toute modification dans le projet, rafraichira le site automatiquement, et buildera des ressources javascript et css optimisés pour la production. Voir également [https://laravel.com/docs/11.x/vite](https://laravel.com/docs/11.x/vite)

# Les tests

**frecole** est testé. Pour le moment majoritairement sur les authorizations (un coup de main est le bienvenu pour le reste....).

Avant de lancer les tests:

- créez votre base de données de test (postgre OU sqlite)
- créez votre environnement de test, voir le fichier *.env.testing*
  
Pour lancer les tests :
> php artisan test

# Les autorisations

**frecole** est fourni avec ces rôles

- ADMIN (administrateur - a accés à tout)
- TEACHER (enseignant - accés limité)
- PARENT (parent - accés limité)
- STUDENT (élève - accés limité)

A chacun de ces rôles correspond une liste de tâches. Voir la matrice de ces tâches ici [https://docs.google.com/spreadsheets/d/1GB4SWRHhzk6gIeP6052KiuQ903_O8UaOWN6J4lz_eBE/edit?usp=sharing](https://docs.google.com/spreadsheets/d/1GB4SWRHhzk6gIeP6052KiuQ903_O8UaOWN6J4lz_eBE/edit?usp=sharing)

L'attribution par défaut des tâches par rôles est défini dans le fichier migration @todo. Libre à vous de modifier cette attribution. **ATTENTION:sujet sensible....**

# Traduction de **frecole**

**frecole** est fourni pas défaut en langue française. Mais l'application est traduite également en langue anglaise.

Pour modifier la langue par défaut, voir le paramétre **APP_LOCALE** dans le fichier d'environnement **.env**

- en français : APP_LOCALE=fr
- en anglais : APP_LOCALE=en

Libre à vous d'ajouter des traductions. Voir le fonctionnement ici [https://laravel.com/docs/11.x/localization](https://laravel.com/docs/11.x/localization)

# Contribution

@todo
