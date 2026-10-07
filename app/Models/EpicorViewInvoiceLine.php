<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpicorViewInvoiceLine extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'p21_view_invoice_line';

    public function invoicehdr()
    {
        return $this->hasOne(EpicorViewInvoiceHdr::class, 'invoice_no', 'invoice_no');
    }
}
