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

class POSSandvikExport implements WithEvents
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
                $templatePath = storage_path('app/template/sandvikpos_template.xlsx');

                if (!file_exists($templatePath)) {
                    return;
                }

                // Wrap the template path into the package's expected TemporaryFile type
                $temporaryFile = new LocalTemporaryFile($templatePath);
                
                // FIX: Pass both the temporary file AND the writer type format
                $event->writer->reopen($temporaryFile, Excel::XLSX);

                // Get the active sheet delegate from the writer to manipulate the layout
                $sheet = $event->writer->getDelegate()->getActiveSheet();

                $currentRow = 4;

                // Process database chunks safely
                EpicorSalesHistory::query()->select('ship2_name', 'ship2_postal_code', 'bill2_name', 'bill2_postal_code', 'item_desc', 'item_id', 'qty_shipped', 'unit_price', 'extended_price', 'unit_of_measure', 'invoice_date', 'source_loc_id', 'source_location_name', 'period', 'year_for_period')->where('supplier_id', '14711')->whereBetween(DB::raw('CAST(invoice_date AS DATE)'), [$this->start, $this->end])->orderBy('invoice_date', 'ASC')->chunk(200, function ($poss) use ($sheet, &$currentRow) {
                        foreach ($poss as $pos) {
                            // Inject values explicitly into matching columns
                            $sheet->setCellValue('A' . $currentRow, $pos->ship2_name);
                            $sheet->setCellValue('B' . $currentRow, $pos->ship2_postal_code);
                            $sheet->setCellValue('C' . $currentRow, $pos->bill2_name);
                            $sheet->setCellValue('D' . $currentRow, $pos->bill2_postal_code);
                            $sheet->setCellValue('E' . $currentRow, $pos->item_desc);
                            $sheet->setCellValue('F' . $currentRow, $pos->item_id);
                            $sheet->setCellValue('G' . $currentRow, $pos->qty_shipped);
                            $sheet->setCellValue('H' . $currentRow, $pos->unit_of_measure);
                            $sheet->setCellValue('I' . $currentRow, $pos->unit_price);
                            $sheet->setCellValue('J' . $currentRow, $pos->extended_price);
                            $sheet->setCellValue('K' . $currentRow, $pos->invoice_date);
                            $sheet->setCellValue('L' . $currentRow, $pos->source_loc_id);
                            $sheet->setCellValue('M' . $currentRow, $pos->source_location_name);
     
                            
                            $currentRow++;
                        }
                    });
            },
        ];
    }


}
