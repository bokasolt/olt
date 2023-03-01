<?php


namespace App\Http\Livewire\Frontend;

use App\Models\Domain;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Purchase extends Component
{
    public $min_order = 20;
    public $max_order = 20;
    public $orderPlaced = false;

    public $urlProcessPayment = '';
    public $quantity = 20;
    public $orderId = 0;
    public $productCode = '';
    public $merchantCode = '';
    public $userId = 0;
    
    protected $rules = [];

    public function __construct($id = null)
    {
        $this->min_order = config('order.min');
        $this->max_order = min(max($this->min_order, Domain::approved()->count()), 1000);
        $this->quantity = $this->min_order;
        $this->rules['quantity'] = 'required|integer|min:'.$this->min_order.'|max:' . $this->max_order;

        $this->merchantCode = config('twocheckout.merchant');
        $this->productCode = config('twocheckout.product_code');
        $this->userId = Auth::user()->id;

        parent::__construct($id);
    }

    public function checkout()
    {
        $this->validate();

        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->quantity = $this->quantity;
        $order->save();

        $this->orderId = $order->id;
        $this->urlProcessPayment = route('frontend.payment.process', ['order' => $this->orderId]);

        $this->orderPlaced = true;
    }

    public function updatedQuantity($value)
    {

        if (((string)intval($this->quantity) != $this->quantity) ||
            ($this->quantity < $this->min_order)) {
             $this->quantity = $this->min_order;
        }
        if ($this->quantity > $this->max_order) {
             $this->quantity = $this->max_order;
        }
    }

    public function render()
    {
        return view('livewire.purchase');
    }
}
