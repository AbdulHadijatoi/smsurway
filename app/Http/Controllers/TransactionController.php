<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Services\DPOService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class TransactionController extends Controller
{
    /**
     * Charge customer's card and update their account balance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\DPOService
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, DPOService $service)
    {
        $validated = $request->validate([
            'amount' => ['bail', 'required', 'integer', 'numeric', 'min:500']
        ]);
        try {
            $dpoToken = $service->createToken(
                $payLoad = $service->preparePayLoad(
                    (float) $validated['amount']
                )
            );

            DB::transaction(function () use ($dpoToken, $payLoad, &$payUrl, $service) {
                $payUrl = $service->generatePayUrl($dpoToken['token']);

                Transactions::create([
                    'tx_id' => $payLoad['CompanyRef'],
                    'tx_ref' => $dpoToken['ref'],
                    'amount' => $payLoad['PaymentAmount'],
                    'feeless_amount' => $payLoad['Amount'],
                    'user_id' => auth()->user()->id,
                    'token' => $dpoToken['token'],
                ]);
            });

            return redirect($payUrl);
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
