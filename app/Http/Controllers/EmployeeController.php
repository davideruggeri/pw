<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DipendenteRepositoryInterface;
use App\Models\Reparto;
use App\Models\Ruolo;
use Illuminate\Http\Request;

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

        $ruoli = Ruolo::all();
        return view('admin.employees.create', compact('reparti', 'ruoli'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'Matricola' => 'required|integer|unique:dipendente,Matricola',
            'Nome' => 'required|string|max:50',
            'Cognome' => 'required|string|max:50',
            'IDReparto_FK' => 'required|exists:reparto,IDReparto',
            'IDRuolo_FK' => 'required|exists:ruolo,IDRuolo',
        ]);

        // Protezione extra per i manager
        if ($user->isManager() && $request->IDReparto_FK != $user->dipendente->IDReparto_FK) {
            abort(403, 'Puoi creare dipendenti solo per il tuo reparto.');
        }

        $this->employeeRepo->create($request->all());

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
        $ruoli = Ruolo::all();
        
        // Mappatura ruoli per reparto (IDReparto => [IDRuolo, ...])
        $roleMapping = [
            5 => [14, 16],         // Amministrazione (Contabile, Manager)
            6 => [15, 16],         // Commerciale (Vendite, Manager)
            4 => [13, 16],         // Logistica (Addetto, Manager)
            1 => [10, 11, 12, 16], // Produzione (Tutti + Manager)
            2 => [10, 11, 12, 16], // Manutenzione
            3 => [10, 11, 12, 16], // Controllo Qualità
        ];

        return view('admin.employees.edit', compact('employee', 'reparti', 'ruoli', 'roleMapping'));
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
            'IDRuolo_FK' => 'required|exists:ruolo,IDRuolo',
        ]);

        // Impedisci al manager di spostare un dipendente in un altro reparto
        if ($user->isManager() && $request->IDReparto_FK != $user->dipendente->IDReparto_FK) {
            abort(403, 'Non puoi spostare dipendenti in altri reparti.');
        }

        $this->employeeRepo->update($matricola, $request->all());

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

        $this->employeeRepo->delete($matricola);
        return redirect()->route('employees.index')->with('success', 'Dipendente rimosso dal sistema.');
    }
}
