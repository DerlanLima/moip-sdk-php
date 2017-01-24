<?php

/**
 * Moip Payments Payments API
 *
 * @since 0.0.1
 * @see http://dev.moip.com.br/referencia-api/#pagamentos Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Payments\Resources;

use DateTime;
use stdClass;
use Softpampa\Moip\Moip;
use Softpampa\Moip\MoipResource;
use Softpampa\Moip\Helpers\Boleto;
use Softpampa\Moip\Helpers\CreditCard;

class Payments extends MoipResource
{

    /**
     * Credit card payment method
     *
     * @const string
     */
    const METHOD_CREDIT_CARD = 'CREDIT_CARD';

    /**
     * Boleto payment method
     *
     * @const string
     */
    const METHOD_BOLETO = 'BOLETO';

    /**
     * Resource path
     *
     * @var string
     */
    protected $resource = 'payments';

    /**
     * Order instance
     *
     * @var \Softpampa\Moip\Payments\Resources\Orders
     */
    private $order;

    /**
     * Initialize a resource
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->data->installmentCount = 1;
        $this->data->fundingInstrument = new stdClass();
        $this->data->fundingInstrument->method = self::METHOD_CREDIT_CARD;
    }

    /**
     * Find a payment
     *
     * @param  int  $payment_id
     * @return $this
     */
    public function find($payment_id)
    {
        return $this->populate($this->client->get('/{payment_id}', [$payment_id]));
    }

    /**
     * Execute a payment
     *
     * @return $this
     */
    public function execute()
    {
        $this->client->setResource($this->order->getResource());

        return $this->client->post('/{order_id}/payments', [$this->order->id], $this->data)->getResults();
    }

    /**
     * Set order
     *
     * @param  \Softpampa\Moip\Payments\Resources\Orders  $order
     * @return $this
     */
    public function setOrder(Orders $order)
    {
        $this->order = $order;

        return $this;
    }

    public function setFundingInstrument(stdClass $fundingInstrument)
    {
        $this->data->fundingInstrument = $fundingInstrument;

        return $this;
    }

    /**
     * Set a credit card
     *
     * @param  \Softpampa\Moip\Helpers\CreditCard  $creditCard
     * @return $this
     */
    public function setCreditCard(CreditCard $creditCard)
    {
        $creditCard->setContext(Moip::PAYMENT);
        $this->data->fundingInstrument->method = self::METHOD_CREDIT_CARD;
        $this->data->fundingInstrument->creditCard = $creditCard->getData();
        //$this->setCreditCardHolder($holder);

        return $this;
    }

    /**
     * Set credit card holder.
     *
     * @param  \Softpampa\Moip\Payments\Resources\Customers  $holder
     * @return void
     */
    private function setCreditCardHolder(Customers $holder)
    {
        $birthdate = $holder->birthDate;

        if ($birthdate instanceof DateTime) {
            $birthdate = $birthdate->format('Y-m-d');
        }

        $this->data->fundingInstrument->creditCard->holder = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->fullname = $holder->fullname;
        $this->data->fundingInstrument->creditCard->holder->birthdate = $birthdate;
        $this->data->fundingInstrument->creditCard->holder->taxDocument = $holder->taxDocument;
        $this->data->fundingInstrument->creditCard->holder->phone = $holder->phone;
    }

    /**
     * Set credit card hash.
     *
     * @param  string  $hash
     * @param  \Softpampa\Moip\Payments\Resources\Customers  $holder
     * @return $this
     */
    public function setCreditCardHash($hash, Customers $holder)
    {
        $this->data->fundingInstrument->method = self::METHOD_CREDIT_CARD;
        $this->data->fundingInstrument->creditCard = new stdClass();
        $this->data->fundingInstrument->creditCard->hash = $hash;
        $this->setCreditCardHolder($holder);

        return $this;
    }

    /**
     * Set boleto payment method
     *
     * @param  \Softpampa\Moip\Helpers\Boleto  $boleto
     * @return $this
     */
    public function setBoleto(Boleto $boleto)
    {
        $boleto->setContext(Moip::PAYMENT);
        $this->data->fundingInstrument->method = self::METHOD_BOLETO;
        $this->data->fundingInstrument->boleto = $boleto->getData();

        return $this;
    }

    /**
     * Set number of installments
     *
     * @param  int  $number
     * @return $this
     */
    public function setInstallmentCount($number)
    {
        $this->data->installmentCount = $number;

        return $this;
    }
}
