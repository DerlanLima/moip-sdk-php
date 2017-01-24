<?php

/**
 * Moip Subscription Customers API
 *
 * @since 0.0.1
 * @see http://dev.moip.com.br/referencia-api/#clientes Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Payments\Resources;

use stdClass;
use DateTime;
use UnexpectedValueException;
use Softpampa\Moip\Moip;
use Softpampa\Moip\MoipResource;
use Softpampa\Moip\Contracts\Holder;
use Softpampa\Moip\Helpers\Phone;
use Softpampa\Moip\Helpers\Address;
use Softpampa\Moip\Helpers\CreditCard;
use Softpampa\Moip\Helpers\TaxDocument;
use Softpampa\Moip\Payments\Events\CustomersEvent;

class Customers extends MoipResource implements Holder
{

    /**
     * Address type billing
     *
     * @const string
     */
    const ADDRESS_BILLING = 'BILLING';

    /**
     * Address type shipping
     *
     * @const string
     */
    const ADDRESS_SHIPPING = 'SHIPPING';

    /**
     * Resource name
     *
     * @var string
     */
    protected $resource = 'customers';

    /**
     * Get all customers
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->client->get()->getResults();
    }

    /**
     * Find a customer
     *
     * @param  int  $customer_id
     * @return $this
     */
    public function find($customer_id)
    {
        return $this->populate($this->client->get('/{customer_id}', [$customer_id]));
    }

    /**
     * Create a customer
     *
     * @param  array  $data
     * @return $this
     */
    public function create($data = [])
    {
        if (! $data) {
            $data = $this->data;
        } else {
            $this->populate($data);
        }

        $response = $this->client->post('', [], $data);

        $this->populate($response);

        if (! $response->hasErrors()) {
            $this->event->dispatch('CLIENT.CREATE', new CustomersEvent($this->data));
        }

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
     * Set customer fullname
     *
     * @param  string  $fullname
     * @return $this
     */
    public function setFullname($fullname)
    {
        $this->data->fullname = $fullname;

        return $this;
    }

    /**
     * Set customer email
     *
     * @param  string  $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->data->email = $email;

        return $this;
    }

    /**
     * Set customer birth date
     *
     * @param  string|DateTime  $date
     * @return $this
     */
    public function setBirthdate($date)
    {
        if ($date instanceof DateTime) {
            $date = $date->format('Y-m-d');
        }

        $this->data->birthDate = $date;

        return $this;
    }

    /**
     * Set customer phone
     *
     * @param  \Softpampa\Moip\Helpers\Phone  $phone
     * @return $this
     */
    public function setPhone(Phone $phone)
    {
        $phone->setContext(Moip::PAYMENT);
        $this->data->phone = $phone->getData();

        return $this;
    }

    /**
     * Set customer tax document
     *
     * @param  \Softpampa\Moip\Helpers\TaxDocument  $taxDocument
     * @return $this
     */
    public function setTaxDocument(TaxDocument $taxDocument)
    {
        $taxDocument->setContext(Moip::PAYMENT);
        $this->data->taxDocument = $taxDocument->getData();

        return $this;
    }

    /**
     * Set customer address
     *
     * @param  string  $type
     * @param  \Softpampa\Moip\Helpers\Address  $address
     * @return $this
     */
    public function addAddress($type = self::ADDRESS_BILLING, Address $address)
    {
        $address->setContext(Moip::PAYMENT);
        $address = $address->getData();

        switch ($type) {
            case self::ADDRESS_BILLING:
                $this->data->billingAddress = $address;
                break;
            case self::ADDRESS_SHIPPING:
                $this->data->shippingAddress = $address;
                break;
            default:
                throw new UnexpectedValueException(sprintf('%s não é um tipo de endereço válido', $type));
        }

        return $this;
    }

    public function getData()
    {
        $holder = new stdClass;
        $holder->fullname = $this->fullname;
        $holder->birthdate = $this->birthDate;
        $holder->phone = $this->phone;
        $holder->taxDocument = $this->taxDocument;

        return $holder;
    }

    /**
     * Set customer credit card
     *
     * @param  \Softpampa\Moip\Helpers\CreditCard  $creditCard
     * @return $this
     */
    public function setCreditCard(CreditCard $creditCard)
    {
        $creditCard->setContext(Moip::PAYMENT);

        $fundingInstrument = new stdClass;
        $fundingInstrument->method = 'CREDIT_CARD';
        $fundingInstrument->creditCard = $creditCard->getData();

        $this->data->fundingInstrument = $fundingInstrument;

        unset($this->data->fundingInstruments);

        return $this;
    }

    /**
     * Add a credit card
     *
     * @param  \Softpampa\Moip\Helpers\CreditCard  $creditCard
     * @return $this
     */
    public function addCreditCard(CreditCard $creditCard)
    {
        $creditCard->setContext(Moip::PAYMENT);

        $fundingInstrument = new stdClass;
        $fundingInstrument->method = 'CREDIT_CARD';
        $fundingInstrument->creditCard = $creditCard->getData();

        $this->data->fundingInstruments[] = $fundingInstrument;

        unset($this->data->fundingInstrument);

        return $this;
    }

    /**
     * Create a new credit card for customer
     *
     * @param  \Softpampa\Moip\Helpers\CreditCard  $creditCard
     * @param  int  $id
     * @return $this
     */
    public function createNewCreditCard(CreditCard $creditCard, $id = null)
    {
        if (! $id) {
            $id = $this->data->id;
        }

        $creditCard->setContext(Moip::PAYMENT);

        $this->data = new stdClass;
        $this->data->method = 'CREDIT_CARD';
        $this->data->creditCard = $creditCard->getData();

        $response = $this->client->post('{id}/fundinginstruments', [$id], $this->data);

        $this->populate($response);

        return $this;
    }
}
