<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EpicorSalesHistory extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'P21_sales_history_view';


    public function item(): HasOne
    {
        return $this->hasOne(EpicorItemView::class, 'item_id', 'item_id')->where('p21_item_view.supplier_id', '=', 13202)->select(['item_id','supplier_part_no', 'upc_code']);
    }
}
