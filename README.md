# API REST

## Description

Projet d'api rest développé à Pop School, durant la session 2016-2017.

L'api fournit une liste d'utilisateurs.

## Structure de données

user :

- firstname : string
- lastname : string
- email : string
- birthday : date
- sex : string
- github : string (optionnel)
- pet : boolean (optionnel)

## Installation des dépendances

    composer install

## Création de la base de données

Exécutez le script `db.sql` qui se trouve dans le dossier `data`

    cd data
    mysql -u root -p < db.sql

## Utilisation

Démarrez un serveur web de développement dans le dossier `web`

	cd web
	php -S localhost:8000

### GET /

Renvoie la doc de l'api

Ouvrez cette page dans un navigateur web

### GET /api/users

Renvoie la liste des utilisateurs

Exemple avec httpie :

    http localhost:8000/api/users/

### GET /api/users/{id}

Renvoie le détail d'un utilisateur

Exemple avec httpie :

    http localhost:8000/api/users/1

### POST /api/users

Ajoute un utilisateur

Exemple avec httpie :

	http --form localhost:8000/api/users/ firstname=Foo2 lastname=Bar email=foo2.bar@pop.eu.com birthday=2017-02-01 github=http://github.com/foo2bar sex=M pet=false

### PUT /api/users/{id}

Modifie un utilisateur

Exemple avec httpie :

    http --form PUT localhost:8000/api/users/2 firstname=Lorem lastname=Ipsum email=lorem.ipsum@pop.eu.com birthday=2000-12-01 github=http://github.com/loremipsum sex=F pet=false

### DELETE /api/users/{id}

Supprime un utilisateur

Exemple avec httpie :

    http DELETE localhost:8000/api/users/1

## Exceptions

En cas d'erreur, l'api renvoie le code http 500 et un objet json ayant la structure suivante:

    {
        "code": 0,
        "error": true,
        "message": "Message d'erreur"
    }

ou celle ci

    {
        "code": 0,
        "error": true,
        "message": [
            "Message d'erreur 1",
            "Message d'erreur 2",
            "Message d'erreur 3"
        ]
    }

## Déboggage

Ajoutez l'option `--body` à votre requête et redirigez la sortie standard vers un fichier avec `>` :

- requête originale

    http --form localhost:8000/api/users/ name="Foo Bar"

- requête de déboggage

    http --body --form localhost:8000/api/users/ name="Foo Bar" > debug.html
