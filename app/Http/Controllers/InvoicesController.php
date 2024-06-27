<?php

namespace App\Http\Controllers;

use App\Models\Student;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class InvoicesController extends Controller
{
    public function __invoke(Student $student)
    {
        $customer = new Buyer([
            'name'          => $student->name,
            'custom_fields' => [
                'email' => $student->email,
            ],
        ]);

        $item = InvoiceItem::make('Service 1')->pricePerUnit(1);

        $invoice = Invoice::make()
            ->buyer($customer)
            ->addItem($item);

        return $invoice->stream();
    }
}
