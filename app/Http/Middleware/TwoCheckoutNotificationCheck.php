<?php
/*
 * https://knowledgecenter.2checkout.com/Documentation/07Commerce/InLine-Checkout-Guide/Signature_validation_for_return_URL_via_InLine_checkout
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TwoCheckoutNotificationCheck
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::error($request);
        if (! $this->validateSignature($request)) {
            $msg = 'Invalid signature.';
            Log::error($msg);
            Log::error(Request::fullUrl());
            Log::error($request);

            return response('Invalid signature', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function validateSignature(Request $request): bool
    {
        $params = $request->query();
        if (! isset($params['signature']) || empty($params['signature'])) {
            return false;
        }
        $signature = $params['signature'];
        unset($params['signature']);

        return $this->encrypt($params) === $signature;
    }


    /**
     * @param array $params
     *
     * @return string
     */
    protected function encrypt(array $params): string
    {
        $serialized = $this->serializeParameters($params);

        if (strlen($serialized) > 0) {
            //echo 'Success: serialized params - ' . $serialized . PHP_EOL;

            return bin2hex(hash_hmac('sha256', $serialized, config('twocheckout.ins_secret_word'), true));
        } else {
            //echo 'Error: serialization parameters are empty' . PHP_EOL;

            return '';
        }
    }


    /**
     * @param array $array
     *
     * @return string
     */
    protected function serializeParameters(array $array): string
    {
        ksort($array);

        $serializedString = '';

        foreach ($array as $value) {
            if (is_array($value)) {
                $serializedString .= $this->serializeParameters($value);
            } else {
                $serializedString .= strlen($value) . $value;
            }
        }

        return $serializedString;
    }
}
