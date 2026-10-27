# Gestionale Vendite & Magazzino

Applicazione web per la gestione di un'azienda che vende prodotti tech e per ufficio: catalogo e ordini dei clienti, approvazione delle vendite, magazzino con allerta sulle scorte e dashboard direzionale con i KPI.

Sviluppata in **Laravel (PHP)** su un database **MySQL** progettato a partire da uno schema relazionale esistente. Progetto universitario (Università degli Studi di Brescia) realizzato da **Davide Ruggeri** e **Simone Musatti**.

<!-- Aggiungi qui 2-3 screenshot (dashboard, catalogo, magazzino):
<p align="center">
  <img width="300" alt="Dashboard" src="..." />
  <img width="300" alt="Catalogo" src="..." />
</p>
-->

## Funzionalità

Ogni area ha il proprio accesso e la propria interfaccia, in base al ruolo dell'utente.

- **Cliente**: catalogo prodotti, preferiti, carrello e storico dei propri ordini.
- **Commerciale**: approvazione o rifiuto degli ordini, archivio vendite, prodotti più venduti. Un ordine viene bloccato se la merce in magazzino non basta.
- **Logistica**: giacenze, carico e scarico, storico dei rifornimenti e **notifiche automatiche** per i prodotti sotto la scorta minima.
- **Amministrazione**: dashboard con fatturato per cliente, ricavi e costi del mese, valore medio degli ordini e performance dei dipendenti, oltre alla gestione di dipendenti e reparti.

Interfaccia responsive con tema chiaro e scuro.

## Aspetti tecnici

- **Architettura MVC** di Laravel, con un livello di **service** (`OrderService`, `AnalyticsService`) che separa la logica di business dai controller.
- **Repository pattern**: accesso ai dati tramite interfacce (`app/Repositories/Interfaces`) e implementazioni Eloquent, collegate nel service container.
- **Transazioni** sul database per le operazioni che modificano più tabelle, come la creazione di un ordine con il decremento delle giacenze.
- **Middleware** per i permessi per ruolo e per il cambio password obbligatorio al primo accesso.
- **Notifiche** Laravel per i nuovi ordini e le scorte in esaurimento.
- **Query di analisi** (aggregazioni, subquery) per i KPI della dashboard.
- Modelli Eloquent mappati su uno **schema relazionale esistente**, con nomi di tabelle e chiavi primarie personalizzati. Gli schemi ER e logico sono nella cartella [`docs/`](docs/).

## Stack

| Area | Tecnologie |
|---|---|
| Backend | PHP 8.3, Laravel 13 |
| Database | MySQL |
| Frontend | Blade, CSS, Vite |

## Avvio in locale

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
# imposta le credenziali MySQL nel file .env
php artisan serve
npm run dev
```

La guida completa, con la configurazione del database e gli **account demo** per ogni ruolo, è in [GUIDA_INSTALLAZIONE.md](GUIDA_INSTALLAZIONE.md).
