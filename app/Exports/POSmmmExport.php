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
                // Path to your existing layout file
                // $start = '2026-06-01';
                //  $end = '2026-07-01';
                $templatePath = storage_path('app/template/mmmpos_template.xlsx');

                if (!file_exists($templatePath)) {
                    return;
                }

                // Wrap the template path into the package's expected TemporaryFile type
                $temporaryFile = new LocalTemporaryFile($templatePath);
                
                // FIX: Pass both the temporary file AND the writer type format
                $event->writer->reopen($temporaryFile, Excel::XLSX);

                // Get the active sheet delegate from the writer to manipulate the layout
                $sheet = $event->writer->getDelegate()->getActiveSheet();

                $currentRow = 2;

                // Process database chunks safely
                EpicorSalesHistory::query()->select('company_id', 'customer_id','ship2_name', 'ship2_address1', 'ship2_address2', 'ship2_city', 'ship2_state', 'ship2_postal_code', 'ship2_country', 'item_id', 'item_desc', 'invoice_date', 'invoice_no', 'qty_shipped', 'unit_of_measure', 'unit_price', 'extended_price')->where('supplier_id', '13202')->whereBetween(DB::raw('CAST(invoice_date AS DATE)'), [$this->start, $this->end])->orderBy('invoice_date', 'ASC')->chunk(200, function ($poss) use ($sheet, &$currentRow) {
                        foreach ($poss as $pos) {
                            // Inject values explicitly into matching columns
                            $sheet->setCellValue('A' . $currentRow, $pos->company_id);
                            $sheet->setCellValue('B' . $currentRow, $pos->customer_id);
                            $sheet->setCellValue('C' . $currentRow, $pos->ship2_name);
                            $sheet->setCellValue('D' . $currentRow, $pos->ship2_address1 . ' ' . $pos->ship2_address2);
                            $sheet->setCellValue('E' . $currentRow, $pos->ship2_city);
                            $sheet->setCellValue('F' . $currentRow, $pos->ship2_state);
                            $sheet->setCellValue('G' . $currentRow, $pos->ship2_postal_code);
                            $sheet->setCellValue('H' . $currentRow, $pos->ship2_country);
                            $sheet->setCellValue('I' . $currentRow, $pos->item_id);
                            $sheet->setCellValue('J' . $currentRow, $pos->item_id);
                            $sheet->setCellValue('K' . $currentRow, $pos->item_id);
                            $sheet->setCellValue('L' . $currentRow, $pos->item_desc);
                            $sheet->setCellValue('M' . $currentRow, Carbon::parse($pos->invoice_date)->format('Ymd'));
                            $sheet->setCellValue('N' . $currentRow, $pos->invoice_no);
                            $sheet->setCellValue('O' . $currentRow, $pos->qty_shipped);
                            $sheet->setCellValue('P' . $currentRow, $pos->unit_of_measure);
                            $sheet->setCellValue('Q' . $currentRow, $pos->unit_price);
                            $sheet->setCellValue('R' . $currentRow, $pos->extended_price);
                            $sheet->setCellValue('S' . $currentRow, $pos->company_id);
                            $sheet->setCellValue('T' . $currentRow, $pos->company_id);
                            $sheet->setCellValue('U' . $currentRow, $pos->company_id);

     
                            
                            $currentRow++;
                        }
                    });
            },
        ];
    }


}
