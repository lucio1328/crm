<?php
namespace App\Services\Invoice;

use App\Models\Offer;
use App\Models\Invoice;
use App\Repositories\Tax\Tax;
use App\Repositories\Money\Money;
use App\Models\Configuration;

class InvoiceCalculator
{
    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var Tax
     */
    private $tax;
    /**
     * @var float
     */
    private $discount;

    public function __construct($invoice)
    {
        if(!$invoice instanceof Invoice && !$invoice instanceof Offer ) {
            throw new \Exception("Not correct type for Invoice Calculator");
        }
        $this->tax = new Tax();
        $this->invoice = $invoice;

        $this->discount = $invoice->remise ?? 0;
    }

    public function getVatTotal()
    {
        $price = $this->getSubTotal()->getAmount();
        return new Money($price * $this->tax->vatRate());
    }


    public function getTotalPrice(): Money
    {
        $price = 0;
        $invoiceLines = $this->invoice->invoiceLines;

        foreach ($invoiceLines as $invoiceLine) {
            $price += $invoiceLine->quantity * $invoiceLine->price;
        }
        $price = $price - ($price * $this->discount);

        return new Money($price);
    }

    public function getSubTotal(): Money
    {
        $price = 0;
        $invoiceLines = $this->invoice->invoiceLines;

        foreach ($invoiceLines as $invoiceLine) {
            $price += $invoiceLine->quantity * $invoiceLine->price;
        }
        $price = $price - ($price * $this->discount);

        return new Money($price / $this->tax->multipleVatRate());
    }

    public function getAmountDue()
    {
        return new Money($this->getTotalPrice()->getAmount() - $this->invoice->payments()->sum('amount'));
    }

    public function getInvoice()
    {
        return $this->invoice;
    }

    public function getTax()
    {
        return $this->tax;
    }
}
