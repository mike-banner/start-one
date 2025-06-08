# Variables
CONTAINER_PHP=symfony-php
URL=http://127.0.0.1:8000

# Démarrer les containers en arrière-plan
up:
	docker-compose up -d --build

# Arrêter les containers
down:
	docker-compose down

# Rebuild les containers
rebuild:
	docker-compose down --volumes --remove-orphans
	docker-compose up -d --build

# Accéder au container PHP en bash
bash:
	docker exec -it $(CONTAINER_PHP) bash

# Lancer les migrations Doctrine
migrate:
	docker exec -it $(CONTAINER_PHP) php bin/console doctrine:migrations:migrate

# Nettoyer le cache Symfony
cc:
	docker exec -it $(CONTAINER_PHP) php bin/console cache:clear

# Lancer les tests PHPUnit
test:
	docker exec -it $(CONTAINER_PHP) ./vendor/bin/phpunit

# Installer les dépendances Composer
composer-install:
	docker exec -it $(CONTAINER_PHP) composer install

# Mettre à jour les dépendances Composer
composer-update:
	docker exec -it $(CONTAINER_PHP) composer update

# Ouvrir automatiquement la page web dans le navigateur (Linux/macOS)
open:
	@if which xdg-open > /dev/null; then xdg-open $(URL); \
	elif which open > /dev/null; then open $(URL); \
	else echo "Commande pour ouvrir le navigateur non trouvée, ouvre manuellement $(URL)"; fi

# Redémarrer le serveur Docker et afficher les logs en temps réel
restart:
	@echo "Arrêt des containers..."
	docker-compose down
	@echo "Démarrage des containers..."
	docker-compose up --build
