Doctrine
========
 
### Créer la bdd pour la première fois :

php app/console doctrine:database:create

### Mettre à jour automatiquement la BDD (après que quelqu'un ait modifié une des entités) :

php app/console doctrine:schema:update --force

### Générer entité (classe du modèle) :

php app/console generate:doctrine:entity

### Générer les CRUD :

php app/console generate:doctrine:crud

### Générer un formulaire basé sur une entité :

php app/console generate:doctrine:form AcmeBlogBundle:Post


Symfony
=======

### Vider le cache (est parfois source d'un bug)

php app/console cache:clear

### Génération de bundle :

php app/console generate:bundle

