# 🏭 Progetto ERP Industriale - Guida Installazione e Demo

Benvenuti nel sistema ERP per la gestione di un'industria ceramica. Questo progetto è stato realizzato per l'esame di **[Nome Esame]** (6 CFU) e implementa una gestione multi-reparto con dashboard dedicate e flussi di dati integrati.

---

## 🚀 1. Installazione e Configurazione

Segui questi passaggi per avviare il progetto in locale:

### Requisiti
*   PHP >= 8.1
*   Composer
*   Node.js & NPM
*   SQLite (configurazione di default)

### Passaggi
1.  **Clona il repository** e posizionati nella cartella del progetto.
2.  **Installa le dipendenze PHP**:
    ```bash
    composer install
    ```
3.  **Installa le dipendenze Frontend**:
    ```bash
    npm install
    npm run dev
    ```
4.  **Configura l'ambiente**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
5.  **Inizializza il Database**:
    Assicurati che il file `database/database.sqlite` esista (puoi crearlo vuoto se necessario).
    ```bash
    php artisan migrate --seed
    ```

---

## 🔑 2. Credenziali di Accesso (Demo)

Per testare le diverse funzionalità del sistema, utilizza i seguenti account predefiniti.  
**Password per tutti gli account:** `password`

| Reparto | Ruolo / Email | Funzionalità Principale |
| :--- | :--- | :--- |
| **Amministrazione** | `admin@azienda.it` | Dashboard Finanziaria (EBITDA), Gestione HR e Reparti |
| **Commerciale** | `sales@azienda.it` | Gestione Vendite, Creazione Ordini, Bestsellers |
| **Produzione** | `produzione@azienda.it` | Registrazione Lotti (Input), Monitoraggio Output |
| **Manutenzione** | `manutenzione@azienda.it` | Registro Interventi, Monitoraggio Downtime Macchine |
| **Qualità** | `qualita@azienda.it` | Test di Conformità, Gestione Scarti e Difetti |
| **Logistica** | `logistica@azienda.it` | Gestione Inventario, Alert Sotto Scorta |
| **Cliente** | `cliente@test.it` | Catalogo Prodotti, Carrello, I Miei Ordini |

---

## 🛠️ 3. Struttura dei Reparti Operativi

Il sistema è suddiviso in moduli specializzati per garantire ordine e scalabilità:

### Reparto 1 & 3: Produzione e Qualità
*   La **Produzione** permette di registrare i lotti realizzati. Il sistema calcola automaticamente i costi energetici stimati.
*   Il **Controllo Qualità** interviene sui lotti prodotti per approvarli o scartarli, generando statistiche sui difetti.

### Reparto 2: Manutenzione
*   Gestisce gli interventi tecnici (ordinari e straordinari).
*   Traccia le ore di fermo macchina (Downtime) e i costi dei ricambi, che influiscono direttamente sul bilancio aziendale.

### Reparto 4: Logistica
*   Monitora le giacenze di magazzino in tempo reale.
*   Ogni vendita effettuata dal reparto commerciale scala automaticamente le quantità disponibili.

### Reparto 5: Amministrazione
*   Dashboard "Master" che consolida i dati di tutti i reparti.
*   Calcolo automatico dell'**EBITDA** sottraendo dai ricavi i costi di produzione, manutenzione, lavoro e scarti.

### Reparto 6: Commerciale
*   Hub per la creazione di ordini di vendita per i clienti registrati.
*   Analisi dei prodotti più redditizi (Margine Lordo).

---
*Progetto realizzato da [Tuo Nome e Cognome]*
