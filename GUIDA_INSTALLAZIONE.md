# Guida all'Installazione del Progetto

Segui questi passaggi per configurare il progetto sul tuo PC locale.

## 1. Prerequisiti
Assicurati di avere installato:
- PHP (>= 8.2)
- Composer
- Node.js & NPM
- MySQL (XAMPP, WAMP o Docker)

## 2. Download e Installazione
Apri il terminale nella cartella dove vuoi salvare il progetto:

```bash
# 1. Clona il repository
git clone https://github.com/davideruggeri/pw.git
cd pw

# 2. Installa le dipendenze PHP
composer install

# 3. Installa le dipendenze JS
npm install
```

## 3. Configurazione Ambiente
1. Crea un file chiamato `.env` partendo da `.env.example` (puoi rinominarlo o copiarlo).
2. Apri il file `.env` e configura i dati del tuo database locale:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nome_del_tuo_db
   DB_USERNAME=root
   DB_PASSWORD=la_tua_password
   ```

## 4. Database e Chiavi
Esegui questi comandi per inizializzare il sistema:

```bash
# Genera la chiave dell'applicazione
php artisan key:generate

# Crea le tabelle e inserisci i dati di test
php artisan migrate --seed
```

## 5. Account di Test
Puoi accedere al sistema usando questi account predefiniti (password: `password`):
- **Admin:** `admin@azienda.it`
- **Venditore:** `sales@azienda.it`
- **Cliente:** `cliente@test.it`

## 6. Avvio del Progetto
Esegui questi due comandi (in due terminali separati o in background):

```bash
# Terminale 1 (Compilazione asset)
npm run dev

# Terminale 2 (Server PHP)
php artisan serve
```

L'applicazione sarà disponibile su `http://localhost:8000`.
