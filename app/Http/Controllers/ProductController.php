<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Visualizza il catalogo prodotti con filtri e paginazione
    public function index(Request $request)
    {
        $query = Prodotto::query();
        $perPage = $request->get('per_page', 12);

        // Applica filtro per categoria se selezionata nel menu a tendina
        if ($request->has('category')) {
            $query->where('IDCategoria_FK', $request->category);
        }

        // Ricerca testuale parziale sulla descrizione del prodotto
        if ($request->has('search')) {
            $query->where('Descrizione', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate($perPage)->withQueryString();
        $categories = Categoria::all();

        // Recupera gli ID dei preferiti dell'utente per evidenziare il "cuore" nel catalogo
        $favoriteIds = [];
        if (auth()->check() && auth()->user()->cliente) {
            $favoriteIds = auth()->user()->cliente->preferiti()
                ->pluck('preferiti.CodiceUnivoco_FK')
                ->map(fn($id) => trim($id))
                ->toArray();
        }

        return view('customer.catalog', compact('products', 'categories', 'favoriteIds', 'perPage'));
    }

    public function show($id)
    {
        $product = Prodotto::findOrFail($id);
        return view('customer.product-detail', compact('product'));
    }

    /* 
       Gestisce l'aggiunta/rimozione di un prodotto dai preferiti (many-to-many).
       Supporta sia richieste standard (form) che asincrone (AJAX).
    */
    public function toggleFavorite(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user || !$user->cliente) {
            $msg = 'Solo i clienti registrati possono salvare preferiti.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 403);
            }
            return back()->with('error', $msg);
        }

        $cliente = $user->cliente;
        $id = trim($id);
        
        $product = Prodotto::where('CodiceUnivoco', $id)->firstOrFail();
        $exactId = $product->CodiceUnivoco;
        
        // Se il prodotto è già nei preferiti lo rimuoviamo (detach), altrimenti lo aggiungiamo (attach)
        $isAdded = $cliente->preferiti()->wherePivot('CodiceUnivoco_FK', $exactId)->exists();
        
        if ($isAdded) {
            $cliente->preferiti()->detach($exactId);
            $isAdded = false;
        } else {
            $cliente->preferiti()->attach($exactId);
            $isAdded = true;
        }

        $status = $isAdded ? 'aggiunto ai' : 'rimosso dai';
        
        // Risposta JSON per aggiornamento dinamico della UI senza ricaricare la pagina
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Prodotto $status preferiti!",
                'count' => $cliente->preferiti()->count(),
                'is_added' => $isAdded
            ]);
        }

        return back()->with('success', "Prodotto $status preferiti!");
    }
}
