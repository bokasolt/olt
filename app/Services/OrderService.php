<?php


namespace App\Services;

use App\Domains\Auth\Services\UserService;
use App\Exceptions\OrderAlreadyProcessed;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @throws \Throwable
     */
    public function processOrder(Order $order, array $vals)
    {
        DB::transaction(function () use ($order, $vals) {
            $order = Order::lockForUpdate()->find($order->id);
            if ($order->processed_at) {
                throw new OrderAlreadyProcessed('Order already processed');
            }

            $this->userService->increaseBalance($order->user, $order->quantity);

            if (isset($vals['refno'])) {
                $order->refno = $vals['refno'];
            }
            if (isset($vals['total'])) {
                $order->total = $vals['total'];
            }
            if (isset($vals['total-currency'])) {
                $order->currency = $vals['total-currency'];
            }
            $order->processed_at = now();
            $order->save();
        });
    }
}
