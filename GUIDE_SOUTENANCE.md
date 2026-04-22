# 📘 Guide de Soutenance - eCabinet (Projet Laravel)

Ce document contient tout ce que vous devez comprendre pour votre présentation devant le jury.

---

## 1. Introduction et Objectif du Projet
**eCabinet** est une application web de gestion de cabinet médical. 
- **Problème résolu** : La lenteur et les erreurs de la gestion papier.
- **Solution** : Une plateforme centralisée pour les médecins, patients, secrétaires et administrateurs.

---

## 2. Architecture Technique (Le côté "Backend")
Le projet utilise le framework **Laravel**, qui suit l'architecture **MVC**.

### A. Le Modèle (M) - La Base de Données
C'est ici qu'on définit la structure des données et les relations.
- **Eloquent ORM** : Laravel nous permet d'interagir avec la base de données sans écrire de SQL complexe.
- **Relations clés** :
    - `User` (Rôles: Admin, Doctor, Secretary, Patient)
    - `RendezVous` : Lie un Patient et un Médecin.
    - `Consultation` : Enregistrée après un RDV.
    - `Ordonnance` : Liée à une consultation.
    - `DoctorAvailability` : Gère le planning de l'emploi du temps.

### B. La Vue (V) - L'Interface
- Utilisation de **Blade**, le moteur de template de Laravel.
- Les vues sont découpées en "layouts" (fichiers de base) et "sections" pour éviter de répéter le code HTML.

### C. Le Contrôleur (C) - La Logique
- Les contrôleurs (ex: `DoctorController`, `PatientController`) reçoivent les requêtes et décident quoi faire (sauvegarder en BD, envoyer un mail, afficher une vue).

---

## 3. UML & Base de Données

### Diagramme de Cas d'Utilisation (Use Case)
Il faut savoir expliquer qui fait quoi :
- **Patient** : S'inscrire, prendre RDV, voir ses ordonnances.
- **Docteur** : Définir ses horaires, valider les RDV, remplir les dossiers médicaux.
- **Admin** : Créer les comptes des médecins et secrétaires, surveiller l'activité.

### Diagramme de Classe (Base de Données)
- **Héritage** : Tous les utilisateurs sont dans la table `users`, mais différenciés par la colonne `role`.
- **Cardinalités** :
    - Un Docteur a **plusieurs** (1-N) disponibilités (`DoctorAvailability`).
    - Un Patient a **plusieurs** (1-N) RendezVous.
    - Une Consultation a **exactement une** (1-1) Ordonnance.

---

## 4. Concepts Laravel Essentiels (À connaître par cœur)

1. **Migrations** : Ce sont les fichiers qui créent les tables de la base de données. C'est le "Version Control" de votre schéma.
2. **Middleware** : Un filtre de sécurité. 
   - *Exemple* : `CheckDoctor` vérifie si l'utilisateur est bien un docteur avant de le laisser entrer sur le planning.
3. **Validation** : Utilisation de `FormRequest` pour vérifier que les données (email correct, mot de passe assez long) sont valides avant de les enregistrer.
4. **Artisan** : L'outil en ligne de commande de Laravel (ex: `php artisan migrate`, `php artisan serve`).

---

## 5. Questions probables du Professeur

**Q1 : Comment sécurisez-vous les données des patients ?**
*Réponse :* On utilise l'authentification native de Laravel. Les mots de passe sont hachés (`Bcrypt`). On utilise des **Middlewares** pour que personne ne puisse voir le dossier d'un patient s'il n'est pas le médecin traitant.

**Q2 : C'est quoi un "Controller" dans votre projet ?**
*Réponse :* C'est le cerveau. Il fait le lien entre la demande de l'utilisateur (URL) et les données (Modèle).

**Q3 : Comment avez-vous géré l'emploi du temps des médecins ?**
*Réponse :* J'ai créé une table `doctor_availabilities`. On y stocke les heures de début et de fin pour chaque jour de la semaine. Lors de la prise de RDV, le système vérifie ces horaires.

**Q4 : Si je veux ajouter un nouveau rôle, comment faites-vous ?**
*Réponse :* On modifie l'Enum `role` dans la migration de la table `users` et on crée un nouveau Middleware pour ce rôle.

**Q5 : Comment gérez-vous l'envoi d'e-mails ?**
*Réponse :* Via les classes `Mail` de Laravel (ex: `AppointmentRequested`). Laravel permet de configurer facilement des services SMTP.

---

## 6. Cartographie Technique (Où se trouve le code ?)

Si le jury vous demande de montrer le code, voici où regarder :

### A. Authentification & Sécurité
- **Routes d'authentification** : `routes/auth.php`
- **Filtres de rôles (Middlewares)** : 
    - `app/Http/Middleware/CheckDoctor.php` (Bloque l'accès aux non-docteurs)
    - `app/Http/Middleware/checkAdmin.php` (Bloque l'accès aux non-admins)
- **Logique de redirection après login** : `routes/web.php` (Route `/dashboard`, lignes 14-20).

### B. Emploi du Temps (Planning)
- **Contrôleur** : `app/Http/Controllers/DoctorController.php`
    - Fonction `schedule()` : Prépare l'affichage du calendrier hebdomadaire.
    - Fonction `saveAvailability()` : Enregistre les horaires choisis par le médecin.
- **Modèle** : `app/Models/DoctorAvailability.php`.
- **Vue** : `resources/views/doctor/schedule.blade.php`.

### C. Rendez-vous (RDV)
- **Demande par le Patient** : `app/Http/Controllers/PatientController.php` -> Fonction `requestAppointment()`.
- **Confirmation par le Docteur** : `app/Http/Controllers/DoctorController.php` -> Fonction `confirmAppointment()`.
- **Validation des données** : `app/Http/Requests/RendezVousRequest.php`.

### D. Dossier Médical & Consultation
- **Affichage du dossier** : `app/Http/Controllers/DoctorController.php` -> Fonction `patientRecord()`.
- **Enregistrement des soins** : `app/Http/Controllers/DoctorController.php` -> Fonction `storeConsultation()`.
- **Modèles de données** : `app/Models/Consultation.php` (Contient poids, tension, diagnostic).

### E. Prescriptions & PDF
- **Génération du PDF** : `app/Http/Controllers/DoctorController.php` -> Fonction `exportOrdonnance()`.
- **Template Visuel du PDF** : `resources/views/doctor/pdf/ordonnance.blade.php`.
- **Bibliothèque utilisée** : `DomPDF` (configurée dans `config/app.php`).

---

## 7. Points forts à souligner durant la démo
1. **Intégrité des données** : Une consultation ne peut pas être créée sans un rendez-vous valide.
2. **Expérience Utilisateur (UX)** : Utilisation de composants modernes et responsives pour les tableaux de bord.
3. **Automatisation** : Envoi automatique de notifications par e-mail (`app/Mail/AppointmentRequested.php`) lorsqu'un RDV est pris.
