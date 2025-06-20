# FSD2425 EINDWERK – Backend

![Laravel Logo](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)

## Over dit project

Dit is de backend van het eindwerk voor FSD2425, gebouwd met [Laravel](https://laravel.com/).  
De backend voorziet een RESTful API voor gebruikers, posts, likes, notificaties, followers en meer.

---

## Features

-   Gebruikersregistratie en authenticatie (Sanctum)
-   Profielbeheer
-   Posts aanmaken, bewerken, verwijderen
-   Liken en unliken van posts
-   Volgen en ontvolgen van gebruikers
-   Notificatiesysteem (voor likes, nieuwe posts van vrienden, comments, etc.)
-   Stadionbezoeken en games
-   API-key beveiliging

---

## Installatie

1. **Clone deze repository:**

    ```bash
    git clone <repo-url>
    cd backend
    ```

2. **Installeer dependencies:**

    ```bash
    composer install
    ```

3. **Kopieer `.env` bestand en pas aan:**

    ```bash
    cp .env.example .env
    ```

    Vul je databasegegevens, API-key, en andere settings in.

4. **Genereer app key:**

    ```bash
    php artisan key:generate
    ```

5. **Voer migraties uit:**

    ```bash
    php artisan migrate
    ```

6. **(Optioneel) Seed de database:**

    ```bash
    php artisan db:seed
    ```

7. **Start de server:**
    ```bash
    php artisan serve
    ```

---

## Belangrijke API Endpoints

| Methode | Endpoint           | Omschrijving                    |
| ------- | ------------------ | ------------------------------- |
| POST    | /api/register      | Registreer een nieuwe gebruiker |
| POST    | /api/login         | Log in                          |
| GET     | /api/posts         | Haal alle posts op              |
| POST    | /api/posts         | Maak een nieuwe post            |
| POST    | /api/like          | Like een post                   |
| DELETE  | /api/unlike        | Unlike een post                 |
| POST    | /api/follow        | Volg een gebruiker              |
| DELETE  | /api/unfollow      | Ontvolg een gebruiker           |
| GET     | /api/followers     | Haal je volgers op              |
| GET     | /api/notifications | Haal je notificaties op         |

> Voor sommige endpoints is authenticatie en een geldige API-key vereist.

---

## Notificatiesysteem

Notificaties worden automatisch aangemaakt bij:

-   Een nieuwe post van een vriend (type: `friend_post`)
-   Een like op je post (type: `like`)
-   Een comment op je post (type: `comment`)
-   Een stadionbezoek van een vriend (type: `visit`)

Je kunt notificaties ophalen via `/api/notifications` en verwijderen via `/api/notifications/{id}`.

---

## Extra

-   [Laravel documentatie](https://laravel.com/docs)
-   [Laracasts tutorials](https://laracasts.com)
-   [Intervention Image package](http://image.intervention.io/) voor image processing

---

## Licentie

MIT
