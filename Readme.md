# Projet MasterIA

## Auteur

- Zizouni Maher       (zizo0003)
- Danneaux Lucas      (dann0008)
- Akir Ahmed          (akir0002)
- Lamps Guillaume     (lamp0013)
- Seddik Mohamed-Aziz (sedd0013)

## Installation / Configuration


### Composer

- Lancer l'installation du packet composer :
> `composer install`
### Serveur Web local

- Lancez votre serveur Web local à la racine de votre projet avec :
> `composer start`

- Lien vers la page web :
>http://127.0.0.1:8000/

- Lien vers la page web dans la VM:
>http://10.31.11.36/

### Style de Codage

#### Afin de respecter la recommandation PSR-12 :

- Affiche les différences entre l'original et ce qui serait corrigé :
>`composer test:cs`

- Modifie les fichiers pour respecter la norme :
>`composer fix:cs`



### Tests

Permet de tester le bon fonctionnement du code en initialisant une base de données de test avant:
>`composer test:codeception`

Pour tester le style de codage et les tests codeception:
>`composer test`

### Configuration de la base de données

Le fichier .env.local contient les informations de connexion de
la base de donnée sous le format `mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8&charset=utf8mb4`

pour générer une base de données avec des données factices:
>`composer db`

### Configuration du Mailer

Le fichier .env.local contient les informations de connexion du serveur SMTP pour Mailer
`MAILER_DSN=smtp://user:pass@smtp.example.com:port`


### Utilisateur de démonstration

Utilisateur avec le ROLE_ADMIN
> mail: admin@test.fr 
> mot de passe: test

Utilisateur avec le ROLE_ENTREPRISE
> mail: entr@test.fr
> mot de passe: test

Utilisateur avec le ROLE_ETUDIANT
> mail: etud@test.fr
> mot de passe: test

Utilisateur avec le ROLE_PROFESSEUR
> mail: prof@test.fr
> mot de passe: test
