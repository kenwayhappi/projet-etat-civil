Voici un exemple complet de fichier `README.md` pour votre projet Laravel d'état civil. Ce fichier fournit toutes les instructions nécessaires pour qu'une personne puisse récupérer le projet depuis Git, configurer l'environnement, installer les dépendances, configurer la base de données (qui est déjà incluse), et lancer l'application. Il est rédigé de manière claire et concise, avec des étapes détaillées.

---

```markdown
# Projet État Civil - Gestion des Actes Administratifs

Bienvenue dans le projet État Civil, une application web développée avec Laravel pour gérer les actes administratifs (naissances, mariages, décès, divorces) avec des rôles d'Administrateur national, Superviseur local et Agent. Ce README vous guide pour configurer et exécuter le projet localement.

## Prérequis

- PHP >= 8.3
- Composer
- Node.js et NPM (pour les assets)
- Serveur web (ex. : intégré avec `php artisan serve` ou Apache/Nginx)
- Base de données MySQL (version 8.0 ou supérieure recommandée)
- Git (pour cloner le dépôt)

## Étapes d'installation

### 1. Cloner le dépôt
Clonez le dépôt Git sur votre machine locale :
```bash
git clone <URL_DU_REPOSITOIRE> projet-etat-civil
cd projet-etat-civil
```

Remplacez `<URL_DU_REPOSITOIRE>` par l'URL de votre dépôt Git (ex. : `https://github.com/votre-utilisateur/projet-etat-civil.git`).

### 2. Installer les dépendances PHP
Assurez-vous que Composer est installé, puis exécutez :
```bash
composer install
```

### 3. Installer les dépendances JavaScript
Installez les assets avec NPM :
```bash
npm install
npm run build
```

### 4. Configurer l'environnement
Copiez le fichier `.env.example` en `.env` :
```bash
cp .env.example .env
```

Ouvrez le fichier `.env` et configurez les variables suivantes :
- `DB_DATABASE=etat_civil` : Nom de la base de données (déjà incluse dans le projet).
- `DB_USERNAME=root` : Utilisateur MySQL (par défaut `root`).
- `DB_PASSWORD=` : Mot de passe MySQL (laissez vide si aucun mot de passe).
- `APP_URL=http://localhost:8000` : URL de l'application.

### 5. Générer la clé de l'application
Générez une clé unique pour Laravel :
```bash
php artisan key:generate
```

### 6. Configurer la base de données
La base de données est déjà incluse dans le projet sous la forme d'un fichier SQL (ex. : `database/database.sql`). Importez-la dans MySQL :
- Ouvrez votre client MySQL (ex. : phpMyAdmin ou ligne de commande).
- Créez une base de données nommée `etat_civil` si elle n'existe pas :
  ```sql
  CREATE DATABASE etat_civil;
  ```
- Importez le fichier SQL :
  ```bash
  mysql -u root -p etat_civil < database/database.sql
  ```
  (Entrez votre mot de passe MySQL si nécessaire.)

### 7. Installer les packages supplémentaires
Certaines dépendances doivent être installées manuellement :
- **DomPDF** (pour générer des PDF) :
  ```bash
  composer require barryvdh/laravel-dompdf
  php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
  ```
- **Maatwebsite/Excel** (pour les exports Excel) :
  ```bash
  composer require maatwebsite/excel
  php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"
  ```

### 8. Lancer les migrations (facultatif)
Si vous modifiez la structure de la base de données, exécutez :
```bash
php artisan migrate
```
(Puisque la base de données est déjà fournie, cette étape est optionnelle sauf si des ajustements sont nécessaires.)

### 9. Lancer le serveur
Démarrez le serveur Laravel :
```bash
php artisan serve
```

Ouvrez votre navigateur et accédez à `http://localhost:8000`.

### 10. Se connecter
- Utilisez les identifiants par défaut inclus dans la base de données :
  - **Administrateur national** : `email: admin@etatcivil.com`, `mot de passe: password`
  - **Superviseur local** : `email: supervisor@etatcivil.com`, `mot de passe: password`
  - **Agent** : `email: agent@etatcivil.com`, `mot de passe: password`
- Connectez-vous via la page `/login`.

## Fonctionnalités principales
- **Administrateur national** : Gestion des centres, utilisateurs, consultation globale des actes, rapports statistiques, exports PDF/Excel, paramètres globaux.
- **Superviseur local** : Validation des actes, consultation des statistiques, réception d'alertes.
- **Agent** : Saisie des actes, consultation des actes de son centre, soumission pour validation.

## Structure du projet
- `app/Http/Controllers/` : Contrôleurs pour les différentes fonctionnalités.
- `database/` : Fichier SQL de la base de données.
- `resources/views/` : Vues Blade pour l'interface.
- `routes/web.php` : Définition des routes.

## Dépendances
- `laravel/framework` : v12.21.0
- `barryvdh/laravel-dompdf` : Pour les PDF
- `maatwebsite/excel` : Pour les exports Excel
- `bootstrap` : v5.3.0 (via CDN)
- `font-awesome` : v6.0.0 (via CDN)

## Contribution
1. Forkez le dépôt.
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/nouvelle-fonction`).
3. Committez vos changements (`git commit -m "Ajout de nouvelle fonctionnalité"`).
4. Poussez vers le dépôt (`git push origin feature/nouvelle-fonction`).
5. Ouvrez une pull request.

## Support
Pour toute question ou problème, contactez-nous à `votre-email@example.com` ou ouvrez un issue sur le dépôt Git.

## Licence
Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

---
```

### **Notes importantes**
1. **Fichier SQL** : Assurez-vous que le fichier `database/database.sql` existe dans le projet et contient la structure et les données initiales (tables `users`, `centers`, `regions`, `departments`, `acts`, etc.). Si ce n'est pas encore fait, exportez votre base de données actuelle avec un outil comme phpMyAdmin ou la commande MySQL :
   ```bash
   mysqldump -u root -p etat_civil > database/database.sql
   ```
2. **Identifiants par défaut** : Modifiez les emails et mots de passe dans le README si vous utilisez des valeurs différentes dans votre base de données.
3. **URL du dépôt** : Remplacez `<URL_DU_REPOSITOIRE>` par l'URL réelle de votre dépôt Git.
4. **Dépendances** : Vérifiez que toutes les dépendances listées sont bien utilisées et mises à jour dans `composer.json`.

Placez ce fichier à la racine de votre projet (`README.md`) et committez-le avec votre code. Une personne pourra alors suivre ces étapes pour tout configurer localement. Dites-moi si vous voulez ajouter ou modifier quelque chose ! 😊 (Il est 10:05 AM WAT le lundi 28 juillet 2025.)
