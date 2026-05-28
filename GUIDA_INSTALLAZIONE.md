# 📦 Progetto Gestionale Vendite & Magazzino - Guida Installazione e Demo

Sistema integrato per la gestione aziendale di inventario, acquisti, vendite e personale, sviluppato su stack moderno con **Laravel 11** e **Vite**.

---

## 🚀 1. Installazione e Configurazione

Segui questi passaggi per avviare il progetto in ambiente locale:

### Requisiti
*   **PHP** >= 8.3
*   **Composer**
*   **Node.js** & **NPM**
*   **MySQL** (schema preesistente/legacy)

### Passaggi
1.  **Clona il repository** e posizionati nella cartella root del progetto.
2.  **Installa le dipendenze Backend (PHP)**:
    ```bash
    composer install
    ```
3.  **Installa le dipendenze Frontend**:
    ```bash
    npm install
    ```
4.  **Configura l'ambiente**:
    Duplica il file di configurazione di esempio e genera la chiave di cifratura:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
5.  **Configura il Database**:
    Apri il file `.env` e inserisci le credenziali di accesso al tuo database MySQL:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nome_del_tuo_database
    DB_USERNAME=root
    DB_PASSWORD=tua_password
    ```
    > **⚠️ ATTENZIONE:** Il sistema mappa uno schema legacy esistente. Non eseguire `php artisan migrate` per evitare di alterare o sovrascrivere le tabelle preesistenti, a meno che tu non stia ripristinando da zero l'ambiente di test con le migrazioni fornite.

6.  **Avvia i server di sviluppo**:
    Per visualizzare l'interfaccia con il design premium (Vite) ed eseguire il backend:
    ```bash
    # In un terminale avvia il server Laravel
    php artisan serve

    # In un secondo terminale avvia il motore di build frontend
    npm run dev
    ```

---

## 🔑 2. Credenziali di Accesso (Demo)

Per accedere alle interfacce e testare i vari flussi operativi, utilizza gli account predefiniti configurati nel seeder.  
**Password di default per tutti gli account:** `password`

| Area / Ruolo | Email | Funzionalità Principali |
| :--- | :--- | :--- |
| **Amministrazione** | `admin@azienda.it` | Dashboard Direzionale (KPI), gestione dipendenti, reparti e supervisione flussi |
| **Commerciale** | `sales@azienda.it` | Supervisione e approvazione/rifiuto ordini clienti, archivio vendite, statistiche bestsellers |
| **Logistica** | `logistica@azienda.it` | Gestione giacenze di magazzino, rifornimenti, storico carichi e alert per prodotti sotto scorta minima |
| **Cliente** | `cliente@test.it` | Consultazione catalogo, carrello, gestione preferiti e tracciamento propri ordini |

> **💡 Suggerimento:** È disponibile una modalità ospite per esplorare liberamente il catalogo prodotti senza autenticazione.

---

## 🛠️ 3. Struttura dei Moduli e Flussi

Il gestionale si focalizza sull'ottimizzazione del ciclo di vendita e sull'efficienza di magazzino, eliminando la complessità industriale:

### 📦 Logistica & Magazzino
*   Monitoraggio in tempo reale della `QuantitaGiacenza`.
*   Segnalazione automatica degli articoli che scendono al di sotto della soglia di `ScortaMinima`.
*   Interfacce dedicate per l'aggiornamento rapido dello stock (Carico/Scarico).
*   **Storico Rifornimenti**: registro completo dei carichi effettuati a magazzino con spesa, quantità e timestamp delle operazioni.

### 🤝 Vendite (Commerciale)
*   Gestione del flusso di acquisto: i clienti ordinano tramite carrello, e i commerciali approvano o rifiutano gli ordini.
*   Controllo di disponibilità integrato: le vendite vengono automaticamente inibite se lo stock a magazzino risulta insufficiente.
*   Consultazione dell'archivio storico delle vendite completate.

### 📊 Direzione (Amministrazione)
*   **Business Intelligence**: dashboard centralizzata per il calcolo del fatturato per cliente e l'analisi temporale dei ricavi.
*   Monitoraggio delle performance del personale dipendente.
*   Calcolo avanzato del Valore Medio dell'Ordine (AOV).

### 🛍️ Area Cliente (B2B / B2C)
*   Catalogo interattivo di prodotti Tech e per Ufficio.
*   Supporto completo a liste dei desideri (Wishlist/Preferiti) e carrello persistente.

---

## 🎨 4. Specifiche di Design & Frontend
L'interfaccia adotta un'estetica **Glassmorphism** su toni Indigo/Slate con font **Instrument Sans**, studiata per offrire un'esperienza utente premium:
*   **Dual Mode**: Pieno supporto nativo a temi **Light** e **Dark** tramite selettore `.dark`.
*   **Modularità CSS**: Le pagine caricano dinamicamente file CSS dedicati per evitare sovraccarichi globali, sfruttando le variabili di design centralizzate in `premium.css`.
