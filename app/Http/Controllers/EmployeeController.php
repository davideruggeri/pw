<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DipendenteRepositoryInterface;
use App\Models\Reparto;
use App\Models\Ruolo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    protected $employeeRepo;

    public function __construct(DipendenteRepositoryInterface $employeeRepo)
    {
        $this->employeeRepo = $employeeRepo;
    }

    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            // Admin globale vede tutti
            $employees = $this->employeeRepo->all();
        } elseif ($user->isManager()) {
            // Il manager vede solo i dipendenti del suo reparto
            $repartoId = $user->dipendente->IDReparto_FK;
            $employees = \App\Models\Dipendente::where('IDReparto_FK', $repartoId)->get();
        } else {
            abort(403, 'Non sei autorizzato a gestire i dipendenti.');
        }

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            $reparti = Reparto::all();
        } elseif ($user->isManager()) {
            // Un manager può creare dipendenti solo per il suo reparto
            $reparti = Reparto::where('IDReparto', $user->dipendente->IDReparto_FK)->get();
        } else {
            abort(403);
        }

        return view('admin.employees.create', compact('reparti'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'Matricola' => 'required|integer|unique:dipendente,Matricola|unique:users,matricola_fk',
            'Nome' => 'required|string|max:50',
            'Cognome' => 'required|string|max:50',
            'IDReparto_FK' => 'required|exists:reparto,IDReparto',
        ], [
            'Matricola.unique' => 'La matricola inserita è già presente nel sistema ed è associata ad un altro collaboratore.',
            'Matricola.required' => 'Il campo matricola è obbligatorio.',
            'Matricola.integer' => 'La matricola deve essere un numero intero.',
        ]);

        // Protezione extra per i manager
        if ($user->isManager() && $request->IDReparto_FK != $user->dipendente->IDReparto_FK) {
            abort(403, 'Puoi creare dipendenti solo per il tuo reparto.');
        }

        DB::transaction(function () use ($request) {
            $dipendente = $this->employeeRepo->create($request->all());

            // Generazione email istituzionale automatica (inizialenome.cognome@azienda.it)
            $iniziale = strtolower(substr($dipendente->Nome, 0, 1));
            $cognome = strtolower(str_replace(' ', '', $dipendente->Cognome));
            $baseEmail = "{$iniziale}.{$cognome}";
            $email = "{$baseEmail}@azienda.it";

            // Gestione omonimi (collisioni)
            $counter = 1;
            while (User::where('email', $email)->exists()) {
                $counter++;
                $email = "{$baseEmail}{$counter}@azienda.it";
            }

            // Mapping del ruolo utente basato esclusivamente sul reparto (IDReparto_FK)
            $role = match ((int)$dipendente->IDReparto_FK) {
                5       => 'admin',
                6       => 'sales',
                4       => 'logistics',
                default => 'customer',
            };

            User::create([
                'name'             => "{$dipendente->Nome} {$dipendente->Cognome}",
                'email'            => $email,
                'password'         => Hash::make('Benvenuto2026!'),
                'role'             => $role,
                'matricola_fk'     => $dipendente->Matricola,
                'password_changed' => false,
            ]);
        });

        return redirect()->route('employees.index')->with('success', 'Dipendente aggiunto con successo.');
    }

    public function edit($matricola)
    {
        $user = auth()->user();
        $employee = $this->employeeRepo->find($matricola);

        // Verifica permessi
        if ($user->isManager() && $employee->IDReparto_FK != $user->dipendente->IDReparto_FK) {
            abort(403, 'Non puoi modificare dipendenti di altri reparti.');
        }

        $reparti = $user->isAdmin() ? Reparto::all() : Reparto::where('IDReparto', $user->dipendente->IDReparto_FK)->get();

        return view('admin.employees.edit', compact('employee', 'reparti'));
    }

    public function update(Request $request, $matricola)
    {
        $user = auth()->user();
        $employee = $this->employeeRepo->find($matricola);

        // Verifica permessi
        if ($user->isManager() && $employee->IDReparto_FK != $user->dipendente->IDReparto_FK) {
            abort(403, 'Non puoi modificare dipendenti di altri reparti.');
        }

        $request->validate([
            'Nome' => 'required|string|max:50',
            'Cognome' => 'required|string|max:50',
            'IDReparto_FK' => 'required|exists:reparto,IDReparto',
        ]);

        // Impedisci al manager di spostare un dipendente in un altro reparto
        if ($user->isManager() && $request->IDReparto_FK != $user->dipendente->IDReparto_FK) {
            abort(403, 'Non puoi spostare dipendenti in altri reparti.');
        }

        DB::transaction(function () use ($matricola, $request, $employee) {
            $this->employeeRepo->update($matricola, $request->all());

            // Aggiorna anche l'utente correlato se esiste
            $associatedUser = $employee->user;
            if ($associatedUser) {
                $role = match ((int)$request->IDReparto_FK) {
                    5       => 'admin',
                    6       => 'sales',
                    4       => 'logistics',
                    default => 'customer',
                };
                $associatedUser->update([
                    'name' => "{$request->Nome} {$request->Cognome}",
                    'role' => $role,
                ]);
            }
        });

        return redirect()->route('employees.index')->with('success', 'Dati dipendente aggiornati.');
    }

    public function destroy($matricola)
    {
        $user = auth()->user();
        $employee = $this->employeeRepo->find($matricola);

        // Verifica permessi
        if ($user->isManager() && $employee->IDReparto_FK != $user->dipendente->IDReparto_FK) {
            abort(403, 'Non puoi eliminare dipendenti di altri reparti.');
        }

        DB::transaction(function () use ($matricola, $employee) {
            // Elimina prima l'utente collegato
            $associatedUser = $employee->user;
            if ($associatedUser) {
                $associatedUser->delete();
            }

            $this->employeeRepo->delete($matricola);
        });

        return redirect()->route('employees.index')->with('success', 'Dipendente rimosso dal sistema.');
    }
}
