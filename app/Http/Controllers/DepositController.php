<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function create()
    {
        $trc20Enabled  = setting('manual_trc20_enabled', 1) == 1;
        $bep20Enabled  = setting('manual_bep20_enabled', 1) == 1;
        $trc20Address  = setting('manual_trc20_address', '');
        $bep20Address  = setting('manual_bep20_address', '');
        $minDeposit    = setting('min_deposit', 25);
        $maxDeposit    = setting('max_deposit', 50000);

        return view('dashboard.deposits.create', compact(
            'trc20Enabled',
            'bep20Enabled',
            'trc20Address',
            'bep20Address',
            'minDeposit',
            'maxDeposit'
        ));
    }

    public function store(Request $request)
    {
        $trc20Enabled = setting('manual_trc20_enabled', 1) == 1;
        $bep20Enabled = setting('manual_bep20_enabled', 1) == 1;

        $allowedNetworks = [];
        if ($trc20Enabled) $allowedNetworks[] = 'trc20';
        if ($bep20Enabled) $allowedNetworks[] = 'bep20';

        $networksRule = 'required|in:' . implode(',', $allowedNetworks ?: ['trc20', 'bep20']);

        $min = setting('min_deposit', 25);
        $max = setting('max_deposit', 50000);

        $request->validate([
            'network' => $networksRule,
            'amount'  => "required|numeric|min:{$min}|max:{$max}",
            'txid'    => 'required|string|max:255|unique:deposits,txid',
            'proof'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('deposit_proofs', 'public');
        }

        Deposit::create([
            'user_id'    => Auth::id(),
            'amount'     => $request->amount,
            'txid'       => $request->txid,
            'network'    => $request->network,
            'proof_path' => $proofPath,
            'status'     => 'pending',
        ]);

        return redirect()->route('dashboard.history', ['type' => 'deposit'])
            ->with('success', 'Deposit request submitted successfully. Your balance will be credited after administrator approval.');
    }
}
