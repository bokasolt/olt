<?php

namespace App\Http\Controllers;

use App\Exceptions\OrderAlreadyProcessed;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function process(Request $request, Order $order, OrderService $orderService)
    {
        try {
            $orderService->processOrder($order, $request->all());
        } catch (OrderAlreadyProcessed $e) {
            return redirect()->route('frontend.user.dashboard', ['#information'])
                ->withFlashSuccess(__($e->getMessage()));
        } catch (\Throwable $e) {
            Log::error($e->getMessage());

            return redirect()->route('frontend.user.dashboard', ['#information'])
                ->withFlashSuccess(__('Unexpected error. Please contact to administrator'));
        }

        return redirect()->route('frontend.user.dashboard', ['#information'])
            ->withFlashSuccess(__('Balance successfully updated.'));
    }
}
