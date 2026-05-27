<?php

namespace App\Http\Controllers;

use App\Models\Reparto;
use App\Models\Dipendente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function index()
    {
        $reparti = Reparto::with(['responsabile', 'dipendenti'])
            ->withCount('dipendenti')
            ->get()
            ->map(function ($reparto) {
                switch ($reparto->IDReparto) {
                    case 4: // Logistica
                        $val = DB::table('prodotto')->sum(DB::raw('QuantitaGiacenza * CostoProduzione'));
                        $reparto->kpi_label = "Valore Asset Magazzino";
                        $reparto->kpi_value = '€ ' . number_format($val, 0, ',', '.');
                        $reparto->kpi_color = 'text-slate-700';
                        break;
                    case 6: // Commerciale
                        $val = DB::table('dettaglio_vendita')
                            ->join('ordine_vendita', 'dettaglio_vendita.IDOrdineVendita_FK', '=', 'ordine_vendita.IDOrdineVendita')
                            ->whereMonth('ordine_vendita.Data', now()->month)
                            ->whereIn('ordine_vendita.Stato', ['Approvato', 'Spedito'])
                            ->selectRaw('SUM(QuantitaRichiesta * PrezzoApplicato) as total')
                            ->value('total') ?? 0;
                        $reparto->kpi_label = "Fatturato Mensile";
                        $reparto->kpi_value = '€ ' . number_format($val, 0, ',', '.');
                        $reparto->kpi_color = 'text-emerald-600';
                        break;
                    default: // Amministrazione (ID 5) o Altri
                        $costoLavoro = $reparto->dipendenti_count * 3500;
                        $reparto->kpi_label = "Costo Lavoro Personale";
                        $reparto->kpi_value = '€ ' . number_format($costoLavoro, 0, ',', '.');
                        $reparto->kpi_color = 'text-red-400';
                        break;
                }
                return $reparto;
            });

        return view('admin.departments.index', compact('reparti'));
    }

    public function show($id)
    {
        $reparto = Reparto::with(['responsabile', 'dipendenti.ruolo'])->findOrFail($id);
        $dipendenti = $reparto->dipendenti;
        
        $stats = [];
        switch ($reparto->IDReparto) {
            case 4: // Logistica
                $stats['titolo'] = "Stato Inventario";
                $stats['kpi1_label'] = "Prodotti Sottoscorta";
                $stats['kpi1_value'] = DB::table('prodotto')->whereRaw('QuantitaGiacenza <= ScortaMinima')->count();
                $stats['kpi2_label'] = "Valore Asset Magazzino";
                $stats['kpi2_value'] = '€ ' . number_format(DB::table('prodotto')->sum(DB::raw('QuantitaGiacenza * CostoProduzione')), 0, ',', '.');
                break;
            case 6: // Commerciale
                $stats['titolo'] = "Performance Commerciali";
                $sales = DB::table('dettaglio_vendita')
                    ->join('ordine_vendita', 'dettaglio_vendita.IDOrdineVendita_FK', '=', 'ordine_vendita.IDOrdineVendita')
                    ->whereMonth('ordine_vendita.Data', now()->month)
                    ->whereIn('ordine_vendita.Stato', ['Approvato', 'Spedito'])
                    ->selectRaw('SUM(QuantitaRichiesta * PrezzoApplicato) as rev, COUNT(DISTINCT IDOrdineVendita) as ord')
                    ->first();
                $stats['kpi1_label'] = "Fatturato Mensile";
                $stats['kpi1_value'] = '€ ' . number_format($sales->rev ?? 0, 0, ',', '.');
                $stats['kpi2_label'] = "Ordini Chiusi";
                $stats['kpi2_value'] = $sales->ord ?? 0;
                break;
            default: // Amministrazione (ID 5)
                $stats['titolo'] = "Costi Amministrativi";
                $stats['kpi1_label'] = "Costo Lavoro Personale";
                $stats['kpi1_value'] = '€ ' . number_format($reparto->dipendenti->count() * 3500, 0, ',', '.');
                $stats['kpi2_label'] = "Budget Formazione";
                $stats['kpi2_value'] = '€ ' . number_format($reparto->dipendenti->count() * 150, 0, ',', '.');
                break;
        }

        return view('admin.departments.show', compact('reparto', 'dipendenti', 'stats'));
    }

    public function setResponsabile(Request $request, $id)
    {
        $request->validate([
            'matricola' => 'required|exists:dipendente,Matricola'
        ]);

        $reparto = Reparto::findOrFail($id);
        $reparto->IDResponsabile_FK = $request->matricola;
        $reparto->save();

        return back()->with('success', 'Responsabile aggiornato con successo.');
    }
}
