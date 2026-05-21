<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, $id)
    {
        $product = Prodotto::find($id);
        if (!$product) {
            $msg = 'Prodotto non trovato.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 404);
            }
            return back()->with('error', $msg);
        }
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->Descrizione,
                "quantity" => 1,
                "price" => $product->PrezzoVendita,
                "image" => "📦" // Placeholder logic
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Prodotto aggiunto al carrello!',
                'count' => count($cart)
            ]);
        }

        return back()->with('success', 'Prodotto aggiunto al carrello!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Prodotto rimosso dal carrello!');
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Carrello aggiornato!');
        }
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return back()->with('error', 'Il carrello è vuoto.');
        }

        $user = auth()->user();
        
        if (!$user || !$user->codice_cliente_fk) {
            return back()->with('error', 'Impossibile identificare il cliente.');
        }

        // Crea Ordine Vendita
        $maxId = \App\Models\OrdineVendita::max('IDOrdineVendita');
        
        $ordine = new \App\Models\OrdineVendita();
        $ordine->IDOrdineVendita = $maxId ? $maxId + 1 : 1;
        $ordine->Data = now();
        $ordine->Stato = 'In Attesa';
        $ordine->CodiceCliente_FK = $user->codice_cliente_fk;
        $ordine->save();

        // Salva i dettagli
        foreach ($cart as $id => $details) {
            \App\Models\DettaglioVendita::create([
                'IDOrdineVendita_FK' => $ordine->IDOrdineVendita,
                'CodiceUnivoco_FK' => $id,
                'QuantitaRichiesta' => $details['quantity'],
                'PrezzoApplicato' => $details['price'],
            ]);
        }

        // Pulisci il carrello
        session()->forget('cart');

        // Notifica il reparto commerciale
        $salesUsers = \App\Models\User::all()->filter(function ($u) {
            return $u->isSales() || $u->isAdmin();
        });

        \Illuminate\Support\Facades\Notification::send($salesUsers, new \App\Notifications\OrderSubmittedNotification($ordine));

        return redirect()->route('customer.orders')->with('success', 'Ordine confermato con successo! In attesa di approvazione.');
    }
}
