<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpicorViewInvoiceHdr extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'p21_view_invoice_hdr';
}
