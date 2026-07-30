<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use App\Models\EpicorSalesHistory;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Events\BeforeWriting;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use Carbon\Carbon;


class POSmmmExport implements WithEvents
{
    protected $start;
    protected $end;
    protected $startRow;

   
    public function __construct(string $start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }


    public function registerEvents(): array
    {
        return [


            BeforeWriting::class => function(BeforeWriting $event) {

                $templatePath = storage_path('app/template/mmm_esker_template.xlsx');

                if (!file_exists($templatePath)) {
                    return;
                }

                $temporaryFile = new LocalTemporaryFile($templatePath);
                
                $event->writer->reopen($temporaryFile, Excel::XLSX);

                $sheet = $event->writer->getDelegate()->getActiveSheet();

                $currentRow = 2;

                EpicorSalesHistory::query()->leftJoin('p21_item_view', function ($join) {
        $join->on('p21_sales_history_view.item_id', '=', 'p21_item_view.item_id')
             ->on('p21_sales_history_view.unit_of_measure', '=', 'p21_item_view.unit_of_measure')
             ->on('p21_sales_history_view.supplier_id', '=', 'p21_item_view.supplier_id');
    })->select('company_id', 'customer_id','ship2_name', 'ship2_address1', 'ship2_address2', 'ship2_city', 'ship2_state', 'ship2_postal_code', 'ship2_country', 'p21_sales_history_view.item_id', 'p21_sales_history_view.item_desc', 'invoice_date', 'invoice_no', 'qty_shipped', 'ship_to_id', 'p21_sales_history_view.unit_size', 'p21_sales_history_view.unit_of_measure', 'unit_price', 'extended_price', 'cogs_amount', 'p21_item_view.supplier_part_no', 'p21_item_view.upc_code')->where('p21_sales_history_view.supplier_id', '13202')->whereBetween(DB::raw('CAST(invoice_date AS DATE)'), [$this->start, $this->end])->orderBy('invoice_date', 'ASC')

                ->chunk(200, function ($poss) use ($sheet, &$currentRow) {
                        foreach ($poss as $pos) {

                            if($pos->cogs_amount > 0) {
                                $unitcog = $pos->cogs_amount / ($pos->qty_shipped / $pos->unit_size);
                            } else {
                                // $unitcog = $pos->cogs_amount;
                                $unitcog = '-' . $pos->cogs_amount / ($pos->qty_shipped / $pos->unit_size);
                            }

                            // if(!empty($pos->upc_code)) {
                            //     $product = $pos->upc_code;

                            // } else if(!empty($pos->supplier_part_no)) {
                            //     $product = $pos->supplier_part_no;

                            // } else {
                            //     $product = $pos->item_id;    
                            // }

                            // Inject values explicitly into matching columns
                            $sheet->setCellValue('A' . $currentRow, 'Industrial Mill & Maintenance Supply');
                            $sheet->setCellValue('B' . $currentRow, '16123881');
                            $sheet->setCellValue('C' . $currentRow, $pos->ship_to_id);
                            $sheet->setCellValue('D' . $currentRow, $pos->ship2_name);
                            $sheet->setCellValue('E' . $currentRow, $pos->ship2_address1 . ' ' . $pos->ship2_address2);
                            $sheet->setCellValue('F' . $currentRow, $pos->ship2_city);
                            $sheet->setCellValue('G' . $currentRow, $pos->ship2_state);
                            $sheet->setCellValue('H' . $currentRow, $pos->ship2_postal_code);
                            $sheet->setCellValue('I' . $currentRow, 'US');
                            $sheet->setCellValue('J' . $currentRow, $pos->item_id);

                            $sheet->setCellValue('K' . $currentRow, $pos->item_desc);
                            $sheet->setCellValue('L' . $currentRow, Carbon::parse($pos->invoice_date)->format('Ymd'));
                            $sheet->setCellValue('M' . $currentRow, $pos->invoice_no);
                            $sheet->setCellValue('N' . $currentRow, $pos->qty_shipped);
                            $sheet->setCellValue('O' . $currentRow, $pos->unit_of_measure);
                            $sheet->setCellValue('P' . $currentRow, $unitcog);                     
                            $sheet->setCellValue('Q' . $currentRow, $pos->cogs_amount);
                            $sheet->setCellValue('R' . $currentRow, 'USD');
                           
                            
                            $currentRow++;
                        }
                    });
            },
        ];
    }


}
