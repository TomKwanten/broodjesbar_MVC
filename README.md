Broodjesbar MVC

Broodjesbar MVC is een full-stack PHP webapplicatie voor het beheren van een broodjesbar.
De applicatie laat toe om broodjes te beheren en bestellingen te plaatsen en op te volgen via een duidelijke en gestructureerde MVC-architectuur.

Dit project werd ontwikkeld met focus op structuur, scheiding van verantwoordelijkheden en onderhoudbaarheid.

Functionaliteiten
Broodjesbeheer

Overzicht van alle broodjes

Nieuw broodje toevoegen

Broodje bewerken

Broodje verwijderen

Bestellingen

Bestelling plaatsen

Bestelling bevestigen

Overzicht van bestellingen

Statusbeheer (bijvoorbeeld nieuw, in behandeling, afgehaald)

Foutafhandeling

Centrale foutpagina via Twig template

Architectuur

De applicatie is opgebouwd volgens het MVC-principe (Model–View–Controller) met een duidelijke scheiding tussen lagen.

Entities

Bevat de domeinmodellen:

Broodje

Bestelling

Status

User

Deze klassen bevatten enkel eigenschappen en getters/setters.

Data (DAO-laag)

Bevat alle database-interactie:

BroodjeDAO

BestellingDAO

StatusDAO

UserDAO

DBConfig

De DAO-klassen zijn verantwoordelijk voor het uitvoeren van queries en het omzetten van databasegegevens naar Entity-objecten.

Business (Service-laag)

BroodjeService

BestellingService

De service-laag bevat de businesslogica en vormt de brug tussen controllers en data-laag.

Controllers

Meerdere controllers behandelen de verschillende functionaliteiten, zoals:

Broodjesoverzicht

Nieuw/bewerken/verwijderen van broodjes

Bestelling aanmaken en bevestigen

Bestellingenoverzicht

Presentation

De view-laag is opgebouwd met Twig templates:

layout

header

broodjesoverzicht

bestelpagina

bevestigingspagina

foutpagina

Gebruikte technologieën

PHP (objectgeoriënteerd)

MVC-architectuur

Twig template engine

Composer (dependency management)

NPM (frontend tooling)

CSS (custom build via input.css → output.css)

MySQL

Database

De applicatie maakt gebruik van een MySQL-database met onder andere volgende entiteiten:

Broodjes

Bestellingen

Statussen

Gebruikers

De databaseverbinding wordt geconfigureerd via Data/DBConfig.php.
De huidige configuratie is ingesteld voor lokale ontwikkeling (localhost).

Installatie

Clone de repository

Installeer PHP dependencies:

composer install

Installeer frontend dependencies:

npm install

Configureer je database (standaard: localhost, poort 3308, database broodjesbar)

Start het project via XAMPP of een andere lokale server

Open index.php in de browser

Wat dit project aantoont

Dit project toont:

Correcte toepassing van MVC-architectuur

Scheiding van verantwoordelijkheden (Separation of Concerns)

Gebruik van het DAO-pattern

Implementatie van een service-laag

Werken met een template engine (Twig)

Dependency management via Composer

Structurering van een schaalbare PHP-applicatie

Mogelijke uitbreidingen

Authenticatiesysteem voor gebruikers

Rolgebaseerde toegangscontrole

API-laag toevoegen

Migratie naar een framework zoals Laravel

Environment-based configuratie (.env)
