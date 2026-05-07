# Guida Installazione e Demo - Esame 6 CFU

Questa guida contiene le istruzioni per configurare il progetto e le credenziali di test per navigare tra i vari reparti dell'azienda.

## 🚀 Installazione Rapida

1.  **Clona il repository**
2.  **Installa le dipendenze**:
    ```bash
    composer install
    npm install
    npm run dev
    ```
3.  **Configura l'ambiente**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4.  **Database**:
    Assicurati che `database.sqlite` esista in `database/`.
    ```bash
    php artisan migrate
    ```

## 🔑 Credenziali di Test

Per scopi dimostrativi, utilizza i seguenti account (Password per tutti: `password`):

| Reparto | Ruolo / Titolo | Email | Funzionalità Principale |
| :--- | :--- | :--- | :--- |
| **Reparto 1** | Produzione | `produzione@azienda.it` | Registrazione Lotti, Output Mensile |
| **Reparto 2** | Manutenzione | `manutenzione@azienda.it` | Log Interventi, Downtime |
| **Reparto 3** | Qualità | `qualita@azienda.it` | Controllo Qualità (Coming Soon) |
| **Reparto 4** | Logistica | `logistica@azienda.it` | Magazzino (Coming Soon) |
| **Reparto 5** | Amministrazione | `admin@azienda.it` | Dashboard Globale, Gestione HR |
| **Reparto 6** | Commerciale | `sales@azienda.it` | Vendite, Creazione Ordini |

| **-** | Cliente | `cliente@test.it` | Catalogo, Ordini, Preferiti |

## 🛠️ Struttura Reparti Implementati

### Reparto 1: Produzione Ceramica
Accessibile tramite l'account **Produzione**. Permette di:
*   Visualizzare il volume totale prodotto nel mese.
*   Registrare nuovi lotti di produzione specificando il prodotto e la quantità.
*   Consultare lo storico dei lotti con stima automatica dei costi energetici.

### Reparto 2: Manutenzione Refrattari
Accessibile tramite l'account **Manutenzione**. Permette di:
*   Monitorare le ore di fermo macchina (Downtime).
*   Registrare interventi ordinari o straordinari.
*   Tenere traccia dei costi dei ricambi utilizzati.

---
*Progetto realizzato per l'esame di [Nome Esame] - [Anno Accademico]*
