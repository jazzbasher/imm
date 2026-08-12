<?php

namespace App\Exports;
use App\Models\EpicorOEHDR;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Events\BeforeWriting;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use Carbon\Carbon;

class SPRemitExport implements WithEvents
{
    
    protected $date;
    protected $startRow;

   
    public function __construct(string $date)
    {
        $this->date = $date;
    }


    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                // Path to your existing layout file
                $templatePath = storage_path('app/template/ad_remittemplate.xlsx');

                if (!file_exists($templatePath)) {
                    return;
                }

                $temporaryFile = new LocalTemporaryFile($templatePath);
                
                $event->writer->reopen($temporaryFile, Excel::XLSX);

                $sheet = $event->writer->getDelegate()->getActiveSheet();

                $currentRow = 2;

                EpicorOEHDR::query()
                    ->select('vendor_id', 'invoice_no', 'invoice_date', 'invoice_amount', 'terms_amount_taken')
                    ->whereNotNull('check_no')
                    ->whereDate('check_date', $this->date)
                    ->whereHas('vendor')
                    ->with(['vendor', 'admap'])
                    ->orderBy('vendor_id')
                    ->chunk(200, function ($remits) use ($sheet, &$currentRow) {
                        foreach ($remits as $remit) {

                            if(empty($remit->admap->supplier_id)) {
                                dd('Missing AD Supplier ID for ' . $remit->vendor->vendor_id . ' ' .  $remit->vendor->vendor_name);
                            }

                            if($remit->admap->is_sp == true) {


                            // Inject values explicitly into matching columns
                            $sheet->setCellValue('A' . $currentRow, $remit->vendor->vendor_name);
                            $sheet->setCellValue('B' . $currentRow, $remit->admap->supplier_id);
                            $sheet->setCellValue('C' . $currentRow, strval($remit->invoice_no));
                            $sheet->setCellValue('D' . $currentRow, Carbon::parse($remit->invoice_date)->format('m/d/Y'));
                            $sheet->setCellValue('E' . $currentRow, $remit->invoice_amount);
                            $sheet->setCellValue('F' . $currentRow, number_format($remit->invoice_amount - $remit->terms_amount_taken, 2, '.', ''));
                            $sheet->setCellValue('G' . $currentRow, $remit->terms_amount_taken);
                            $sheet->setCellValue('H' . $currentRow, '0');
                            
                            $currentRow++;
                        }
                    }
                    });
            },
        ];
    }







}




