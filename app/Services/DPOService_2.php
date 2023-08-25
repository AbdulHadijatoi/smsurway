<?php

namespace App\Services;

use App\Models\Transactions;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Spatie\ArrayToXml\ArrayToXml;
use Throwable;

class DPOService
{
    /**
     * Generate payment url for specific transaction.
     *
     * @param  string  $token
     * @return string
     */
    public function generatePayUrl(string $token): string
    {
        $result = $this->verifyToken($token);

        if ($result === '900') {
            return get_setting('dpo_pay_url')->value . $token;
        }

        throw new Exception(
            "We are unable to generate a payment token for you at the moment, please try again"
        );
    }

    /**
     * Verify transaction token's validity.
     *
     * @param  string  $token
     * @return string
     */
    public function verifyToken(string $token): string
    {
        $payLoad = [
            'CompanyToken' => get_setting('dpo_company_token')->value,
            'Request' => 'verifyToken',
            'TransactionToken' => $token
        ];

        $body = ArrayToXml::convert($payLoad, 'API3G', xmlEncoding: 'UTF-8');

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'text/xml;charset=utf-8'
            ])->send('POST', get_setting('dpo_endpoint')->value, ['body' => $body]);

            if ($response = simplexml_load_string($response->body())) {
                $response = json_decode(json_encode($response));

                return $response->Result;
            }
        } catch (Throwable $e) {
            throw new Exception(
                code: 500,
                message: "We are unable to process payment at the moment, please try again.",
            );
        }
    }

    /**
     * Create transaction token on DPO.
     *
     * @param  array  $payLoad
     * @return array
     */
    public function createToken(array $payLoad): array
    {
        $payLoad = [
            'CompanyToken' => get_setting('dpo_company_token')->value,
            'Request' => 'createToken',
            'Transaction' => $payLoad,
            'Services' => [
                'Service' => [
                    'ServiceType' => get_setting('dpo_service_id')->value,
                    'ServiceDescription' => 'Credit purchase',
                    'ServiceDate' => now()->toDateTimeString(),
                ]
            ]
        ];

        $body = ArrayToXml::convert($payLoad, 'API3G', xmlEncoding: 'UTF-8');

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'text/xml;charset=utf-8'
            ])->send('POST', get_setting('dpo_endpoint')->value, ['body' => $body]);

            if ($response = simplexml_load_string($response->body())) {
                $response = json_decode(json_encode($response));

                if ($response->Result === '000') {
                    return [
                        'ref' => $response->TransRef,
                        'token' => $response->TransToken,
                        'explanation' => $response->ResultExplanation
                    ];
                } else {
                    throw new Exception($response->ResultExplanation);
                }
            }
        } catch (Throwable $e) {
            throw new Exception(
                code: 500,
                message: "We are unable to process payment at the moment, please try again.",
            );
        }
    }

    /**
     * Construct a payload for self::createToken().
     *
     * @param  float  $amount
     * @return array
     */
    public function preparePayLoad(float $amount): array
    {
        return [
            'PTL' => 5,
            'PaymentAmount' => $amount,
            'PaymentCurrency' => get_setting('currency')->value,
            'RedirectURL' => route('buy'),
            'BackURL' => route('buy', ['cancel' => now()->timestamp]),
            'customerEmail' => auth()->user()->email,
            'CompanyRef' => md5(uniqid().now()->addMinutes(random_int(1, 99999))),
            'customerPhone' => auth()->user()->mobile,
            'customerCountry' => auth()->user()->country_code ?? 'ng'
        ];
    }

    /**
     * Update transaction record and update user's credit.
     *
     * @param  \App\Models\Transactions  $transaction
     * @return void
     */
    public function updateTransactionRecord(Transactions $transaction)
    {
        DB::transaction(function () use ($transaction) {
            $transaction->paid = true;

            $transaction->save();

            auth()->user()->credit = auth()->user()->credit + $transaction->amount;

            auth()->user()->save();
        });
    }
}
