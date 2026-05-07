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
                // Calcoliamo i KPI specifici per ogni reparto in base all'ID
                switch ($reparto->IDReparto) {
                    case 1: // Produzione
                        $val = DB::table('produzione_log')->whereMonth('DataProduzione', now()->month)->sum('QuantitaProdotta');
                        $reparto->kpi_label = "Output Mensile";
                        $reparto->kpi_value = number_format($val, 0, ',', '.') . ' kg';
                        $reparto->kpi_color = 'text-indigo-600';
                        break;
                    case 2: // Manutenzione
                        $val = DB::table('manutenzione_log')->whereMonth('DataIntervento', now()->month)->sum('OreFermoMacchina');
                        $reparto->kpi_label = "Downtime Mensile";
                        $reparto->kpi_value = $val . ' ore';
                        $reparto->kpi_color = 'text-amber-600';
                        break;
                    case 4: // Logistica
                        $val = DB::table('prodotto')->sum(DB::raw('QuantitaGiacenza * CostoProduzione'));
                        $reparto->kpi_label = "Valore Asset Magazzino";
                        $reparto->kpi_value = '€ ' . number_format($val, 0, ',', '.');
                        $reparto->kpi_color = 'text-slate-700';
                        break;
                    case 3: // Qualità (Prima era 5)
                        $scarti = DB::table('qualita_log')->whereMonth('DataControllo', now()->month)->sum('QuantitaScartata');
                        $prod = DB::table('produzione_log')->whereMonth('DataProduzione', now()->month)->sum('QuantitaProdotta');
                        $tasso = ($prod > 0) ? ($scarti / $prod) * 100 : 0;
                        $reparto->kpi_label = "Tasso di Scarto";
                        $reparto->kpi_value = number_format($tasso, 2) . '%';
                        $reparto->kpi_color = $tasso > 3 ? 'text-red-600' : 'text-emerald-600';
                        break;
                    case 6: // Commerciale
                        $val = DB::table('dettaglio_vendita')
                            ->join('ordine_vendita', 'dettaglio_vendita.IDOrdineVendita_FK', '=', 'ordine_vendita.IDOrdineVendita')
                            ->whereMonth('ordine_vendita.Data', now()->month)
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
            case 1: // Produzione
                $stats['titolo'] = "Performance Produzione";
                $stats['kpi1_label'] = "Totale Prodotto (Mese)";
                $stats['kpi1_value'] = number_format(DB::table('produzione_log')->whereMonth('DataProduzione', now()->month)->sum('QuantitaProdotta'), 0, ',', '.') . ' kg';
                $stats['kpi2_label'] = "Costo Energetico";
                $stats['kpi2_value'] = '€ ' . number_format(DB::table('produzione_log')->whereMonth('DataProduzione', now()->month)->sum('CostoEnergiaStimato'), 0, ',', '.');
                break;
            case 2: // Manutenzione
                $stats['titolo'] = "Interventi Tecnici";
                $stats['kpi1_label'] = "Ore Fermo Macchina";
                $stats['kpi1_value'] = DB::table('manutenzione_log')->whereMonth('DataIntervento', now()->month)->sum('OreFermoMacchina') . ' h';
                $stats['kpi2_label'] = "Costi Ricambi";
                $stats['kpi2_value'] = '€ ' . number_format(DB::table('manutenzione_log')->whereMonth('DataIntervento', now()->month)->sum('CostoRicambi'), 0, ',', '.');
                break;
            case 4: // Logistica
                $stats['titolo'] = "Stato Inventario";
                $stats['kpi1_label'] = "Prodotti Sottoscorta";
                $stats['kpi1_value'] = DB::table('prodotto')->whereRaw('QuantitaGiacenza <= ScortaMinima')->count();
                $stats['kpi2_label'] = "Valore Asset Magazzino";
                $stats['kpi2_value'] = '€ ' . number_format(DB::table('prodotto')->sum(DB::raw('QuantitaGiacenza * CostoProduzione')), 0, ',', '.');
                break;
            case 3: // Qualità (Prima era 5)
                $stats['titolo'] = "Metriche Qualità";
                $scarti = DB::table('qualita_log')->whereMonth('DataControllo', now()->month)->sum('QuantitaScartata');
                $prod = DB::table('produzione_log')->whereMonth('DataProduzione', now()->month)->sum('QuantitaProdotta');
                $stats['kpi1_label'] = "Tasso di Scarto";
                $stats['kpi1_value'] = number_format(($prod > 0) ? ($scarti / $prod) * 100 : 0, 2) . '%';
                $stats['kpi2_label'] = "Kg Scartati";
                $stats['kpi2_value'] = number_format($scarti, 0, ',', '.') . ' kg';
                break;
            case 6: // Commerciale
                $stats['titolo'] = "Performance Commerciali";
                $sales = DB::table('dettaglio_vendita')->join('ordine_vendita', 'dettaglio_vendita.IDOrdineVendita_FK', '=', 'ordine_vendita.IDOrdineVendita')->whereMonth('ordine_vendita.Data', now()->month)->selectRaw('SUM(QuantitaRichiesta * PrezzoApplicato) as rev, COUNT(DISTINCT IDOrdineVendita) as ord')->first();
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
