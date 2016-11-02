<?php

/**
 * Moip Subscription Orders API
 *
 * @since 0.0.1
 * @see http://dev.moip.com.br/referencia-api/#pedidos Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Payments\Resources;

use stdClass;
use Softpampa\Moip\MoipResource;
use Softpampa\Moip\Payments\Events\OrdersEvent;

class Orders extends MoipResource {

    /**
     * Resource name
     *
     * @var string
     */
    protected $resource = 'orders';

    /**
     * Default amount currency
     *
     * @const string
     */
    const AMOUNT_CURRENCY = 'BRL';

    /**
     * Initialize Orders Data Object
     *
     * @return void
     */
    protected function initialize()
    {
        parent::initialize();

        $this->data = new stdClass();
        $this->data->ownId = null;
        $this->data->amount = new stdClass();
        $this->data->amount->currency = self::AMOUNT_CURRENCY;
        $this->data->amount->subtotals = new stdClass();
        $this->data->items = [];
        //$this->data->receivers = [];
    }

    /**
     * Get all orders
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->client->get()->getResults();
    }

    /**
     * Find a order
     *
     * @param  int  $order_id
     * @return $this
     */
    public function find($order_id)
    {
        $response = $this->client->get('/{order_id}', [$order_id]);

        $this->populate($response);

        return $this;
    }

    /**
     * Create a order
     *
     * @param  array  $data
     * @return $this
     */
    public function create($data = [])
    {

        if (!$data) {
            $data = $this->data;
        } else {
            $this->populate($data);
        }

        $response = $this->client->post('', [], $data);

        $this->populate($response);

        if (! $response->hasErrors()) {
            $this->event->dispatch('ORDER.CREATED', new OrdersEvent($this->data));
        }

        return $this;
    }

    public function payments()
    {
        $payment = new Payments($this->api);
        $payment->setOrder($this);

        return $payment;
    }

    /**
     * Add item to a order
     *
     * @param  string  $product
     * @param  int  $quantity
     * @param  string  $detail
     * @param  float  $price
     * @return $this
     */
    public function addItem($product, $quantity, $detail, $price)
    {
        $item = new stdClass();
        $item->product = $product;
        $item->quantity = $quantity;
        $item->detail = $detail;
        $item->price = (float) $price;

        $this->data->items[] = $item;

        return $this;
    }

    /**
     * Set your own id
     *
     * @param  int  $ownId
     * @return $this
     */
    public function setOwnId($ownId)
    {
        $this->data->ownId = $ownId;

        return $this;
    }

    /**
     * Set a new Customer
     *
     * @param  Customers  $customer
     * @return $this
     */
    public function setNewCustomer(Customers $customer)
    {
        $this->data->customer = $customer;

        return $this;
    }

    /**
     * Set a new Customer
     *
     * @param  \Softpampa\Moip\Payments\Resources\Customers  $customer
     * @return $this
     */
    public function setCustomer($customer)
    {
        $this->data->customer = new stdClass;
        $this->data->customer = $customer;

        return $this;
    }

    /**
     * Set a value addition
     *
     * @param  int  $value
     * @return $this
     */
    public function setAddition($value)
    {
        $this->data->amount->subtotals->addition = (float) $value;

        return $this;
    }

    /**
     * Add a value addition
     *
     * @param  int  $value
     * @return $this
     */
    public function addAddition($value)
    {
        $this->data->amount->subtotals->addition += (float) $value;

        return $this;
    }

    /**
     * Set a value discount
     *
     * @param  int  $value
     * @return $this
     */
    public function setDiscount($value)
    {
        $this->data->amount->subtotals->discount = (float) $value;

        return $this;
    }

    /**
     * Add a value discount
     *
     * @param  int  $value
     * @return $this
     */
    public function addDiscount($value)
    {
        $this->data->amount->subtotals->discount -= (float) $value;

        return $this;
    }

    /**
     * Set a value for shipping
     *
     * @param  int  $value
     * @return $this
     */
    public function setShippingAmount($value)
    {
        $this->data->amount->subtotals->shipping = (float) $value;

        return $this;
    }

}
