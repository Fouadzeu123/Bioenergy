<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 25);
        $search  = $request->get('q');
        $type    = $request->get('type');
        $status  = $request->get('status');

        $query = Transaction::with(['user:id,phone,level'])
            ->select('transactions.*')
            ->leftJoin('users', 'transactions.user_id', '=', 'users.id');

        // Recherche
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('transactions.reference', 'like', "%{$search}%")
                  ->orWhere('users.phone', 'like', "%{$search}%");
            });
        }

        // Filtres
        if ($type) {
            if ($type === 'bonus_all') {
                $query->where('transactions.type', 'LIKE', 'bonus%');
            } else {
                $query->where('transactions.type', $type);
            }
        }

        if ($status) {
            $query->where('transactions.status', $status);
        }

        // Tri par défaut
        $transactions = $query->orderByDesc('transactions.created_at')
                              ->paginate($perPage)
                              ->withQueryString();

        // Stats rapides pour la page
        $stats = [
            'total'            => Transaction::count(),
            'pending_retraits' => Transaction::where('type', 'retrait')->where('status', 'pending')->count(),
            'today_completed'  => Transaction::whereDate('created_at', today())->where('status', 'completed')->count(),
            'total_amount' => Transaction::where('status', 'completed')->sum('montant'),
        ];

        return view('admin.transaction-index', compact('transactions', 'stats'));
    }

}