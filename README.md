# socialNetwork-phpV2

## Commande pour lancer le repo socialNetwork-v2



###Installer composer
Running ```composer install```


### Créer la base de donnée
Running ``` php bin/console doctrine:database:create ```

### Migration de la BDD
Running ```php bin/console make:migration```

### Initialisation de la BDD
Running ```php bin/console doctrine:schema:update —force```

### Ajouter le module faker
Running ```composer require fzaninotto/faker``` 

### Charger la BDD
Running ```php bin/console doctrine:fixtures:load``` 

### Effectuer la migration
Running ```php bin/console doctrine:migrations:migrate```
