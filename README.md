# Progetto Gestionale Vendite & Magazzino

Sistema di gestione aziendale integrato sviluppato con Laravel 11, basato su un sistema di database preesistente per la gestione di inventario, acquisti, vendite e personale.

## 📌 Mapping Database (Legacy Schema)
**IMPORTANTE:** I modelli Laravel devono mappare lo schema esistente rispettando i nomi delle tabelle e le chiavi primarie personalizzate.
- **PRODOTTO**: Tabella `prodotto`. PK: `CodiceUnivoco`. Relazione con `CATEGORIA` tramite `IDCategoria_FK`.
- **CLIENTE**: Tabella `cliente`. PK: `CodiceCliente`.
- **ORDINE VENDITA**: Tabella `ordine_vendita`. PK: `IDOrdineVendita`. Relazione con `CLIENTE` tramite `CodiceCliente_FK`.
- **DIPENDENTE**: Tabella `dipendente`. PK: `Matricola`. Relazione con `REPARTO` e `RUOLO`.
- **ASSOCIAZIONI N:M**: Le tabelle `DETTAGLIO_VENDITA` e `DETTAGLIO_ACQUISTO` gestiscono i prodotti negli ordini con quantità e prezzi specifici.

## 🚀 Funzionalità Principali
- **Gestione Inventario**: Monitoraggio delle giacenze e segnalazione automatica dei prodotti sotto `ScortaMinima`.
- **Ciclo Vendite**: Registrazione ordini con decremento automatico della `QuantitaGiacenza`. La vendita deve essere bloccata se la disponibilità è insufficiente.
- **Business Intelligence (KPI)**: 
    - Calcolo del fatturato totale per cliente.
    - Analisi delle performance dei dipendenti per anno.
    - Calcolo del Valore Medio Ordine (AOV) tramite subquery.
    - Analisi temporale del fatturato mensile.

## 🎨 Design System
Il progetto utilizza un'interfaccia moderna e responsive:
- **UI Style**: Glassmorphism UI.
- **Palette**: Indigo / Slate.
- **Tipografia**: Instrument Sans.
- **Layout**: Dashboard strutturata con distinzione tra informazioni pubbliche e amministrative.

### 📐 Linee Guida Sviluppo Frontend
Per mantenere la pulizia e la manutenibilità del codice, ogni nuova implementazione deve seguire queste regole:
1. **Separazione CSS**: Ogni nuova pagina Blade **deve** avere il proprio file CSS dedicato in `public/css/`. Non utilizzare stili inline o utility classes (Tailwind) in modo massiccio.
2. **Inclusione Asset**: Utilizzare `@push('styles')` nella vista Blade per caricare il file CSS specifico.
3. **Dual Mode Support**: Ogni modifica o nuovo stile deve essere testato e funzionante sia in **Light Mode** che in **Dark Mode**. Utilizzare il selettore `.dark` nel CSS per gestire le variazioni cromatiche.
4. **Variabili CSS**: Dove possibile, utilizzare le variabili definite in `premium.css` per garantire coerenza cromatica.

## 🛠 Stack Tecnologico
- **Backend**: PHP 8.3+ / Laravel 11.
- **Frontend**: Vite, Vanilla CSS (Premium Custom Design).
- **Database**: MySQL.

## 🏛️ Architettura e Pattern
Il progetto è sviluppato seguendo i più alti standard di ingegneria del software:
- **Pattern Architetturale MVC**: Separazione netta tra logica dei dati (Model), interfaccia utente (View) e logica di controllo (Controller).
- **Gang of Four (GoF) Design Patterns**:
    - **Factory Pattern**: Utilizzato per la generazione controllata di modelli e oggetti di test.
    - **Facade Pattern**: Laravel Facades utilizzate per l'accesso semplificato ai servizi di core (Auth, DB, Hash).
    - **Chain of Responsibility**: Implementato tramite i *Middleware* per la gestione sequenziale dei permessi e delle verifiche (es. cambio password obbligatorio).
    - **Singleton Pattern**: Utilizzato dal Service Container di Laravel per la gestione delle istanze univoche dei servizi.
    - **Strategy Pattern**: Utilizzato nella logica di reindirizzamento post-login in base al ruolo dell'utente.

## ⚙️ Installazione & Setup
1. Clona il repository.
2. Esegui `composer install` e `npm install`.
3. Configura il file `.env` con le credenziali del database MySQL esistente.
4. **ATTENZIONE**: Non eseguire `php artisan migrate` per evitare di sovrascrivere lo schema legacy, a meno di non aver creato migrazioni speculari.
5. Avvia il server: `php artisan serve` e `npm run dev`.