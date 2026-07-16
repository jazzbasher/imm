<?php

namespace App\Exports;
use App\Models\EpicorOEHDR;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Events\BeforeWriting;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use Carbon\Carbon;

class ADRemitExport implements WithEvents
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

                // Wrap the template path into the package's expected TemporaryFile type
                $temporaryFile = new LocalTemporaryFile($templatePath);
                
                // FIX: Pass both the temporary file AND the writer type format
                $event->writer->reopen($temporaryFile, Excel::XLSX);

                // Get the active sheet delegate from the writer to manipulate the layout
                $sheet = $event->writer->getDelegate()->getActiveSheet();

                $currentRow = 2;

                // Process database chunks safely
                EpicorOEHDR::query()
                    ->select('vendor_id', 'invoice_no', 'invoice_date', 'invoice_amount', 'terms_amount_taken')
                    ->whereNotNull('check_no')
                    ->whereDate('check_date', $this->date)
                    ->whereHas('vendor')
                    ->with(['vendor', 'address'])
                    ->orderBy('vendor_id')
                    ->chunk(200, function ($remits) use ($sheet, &$currentRow) {
                        foreach ($remits as $remit) {
                            // Inject values explicitly into matching columns
                            $sheet->setCellValue('A' . $currentRow, $remit->vendor->vendor_name);
                            $sheet->setCellValue('B' . $currentRow, (int)preg_replace('/[^0-9]/', '', $remit->address->mail_address1));
                            $sheet->setCellValue('C' . $currentRow, strval($remit->invoice_no));
                            $sheet->setCellValue('D' . $currentRow, Carbon::parse($remit->invoice_date)->format('m/d/Y'));
                            $sheet->setCellValue('E' . $currentRow, $remit->invoice_amount);
                            $sheet->setCellValue('F' . $currentRow, number_format($remit->invoice_amount - $remit->terms_amount_taken, 2, '.', ''));
                            $sheet->setCellValue('G' . $currentRow, $remit->terms_amount_taken);
                            
                            $currentRow++;
                        }
                    });
            },
        ];
    }







}











    // public function query()
    // {

    //     return EpicorOEHDR::query()->select('vendor_id','invoice_no', 'invoice_date', 'invoice_amount', 'terms_amount_taken')->whereNotNull('check_no')->whereDate('check_date', $this->date)->whereHas('vendor')->with('vendor')->with('address')->orderBy('vendor_id');
    // }

  

   
    // public function map($remit): array
    // {
    //     return [
    //         $remit->vendor->vendor_name,
    //         (int)preg_replace('/[^0-9]/', '', $remit->address->mail_address1),
    //         strval($remit->invoice_no),
    //         Carbon::parse($remit->invoice_date)->format('m/d/Y'),
    //         $remit->invoice_amount,
    //         number_format($remit->invoice_amount - $remit->terms_amount_taken, 2, '.', ''),
    //         $remit->terms_amount_taken,
    //     ];
    // }




    // public function registerEvents(): array
    // {
    //     return [
    //         BeforeWriting::class => function(BeforeWriting $event) {
    //             // Path to your existing template file
    //             $templatePath = storage_path('app/template/ad_remittemplate.xlsx');

    //             if (file_exists($templatePath)) {
    //                 // Create a safe temporary copy
    //                 $tempFile = tempnam(sys_get_temp_dir(), 'export') . '.xlsx';
    //                 copy($templatePath, $tempFile);

    //                 // Force the writer to load the template before chunk writing begins
    //                 $localTempFile = new LocalTemporaryFile($tempFile);
    //                 $event->writer->reopen($localTempFile, ExcelType::XLSX);
                    
    //                 // Direct chunk data into the first sheet without changing its layout
    //                 $event->writer->getSheetByIndex(0);
    //             }
    //         },
    //     ];
    // }






    //  public function registerEvents(): array
    // {
    //     return [
    //         BeforeWriting::class => function(BeforeWriting $event) {
    //             $filePath = storage_path('app/template/ad_remittemplate.xlsx');

    //             if (file_exists($filePath)) {
    //                 // Create a temporary copy to avoid locking the original file
    //                 $tempFile = tempnam(sys_get_temp_dir(), 'export') . '.xlsx';
    //                 copy($filePath, $tempFile);

    //                 // Reopen the template in PhpSpreadsheet and append to Sheet 1
    //                 $templateFile = new LocalTemporaryFile($tempFile);
    //                 $event->writer->reopen($templateFile, Excel::XLSX);
                    
    //                 // Appends without overwriting the existing structure/styles
    //                 $event->writer->getSheetByIndex(0);
    //             }
    //         },
    //     ];
    // }






    //  public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function(AfterSheet $event) {
    //             // Load the predefined .xlsx template from storage
    //             $templatePath = storage_path('app/template/ad_remittemplate.xlsx');
    //             $templateSpreadsheet = IOFactory::load($templatePath);
                
    //             // Get the active sheet from the template
    //             $templateSheet = $templateSpreadsheet->getActiveSheet();
                
    //             // Copy all elements/styles to the active export sheet
    //             $event->sheet->getDelegate()->fromArray(
    //                 $templateSheet->toArray(), 
    //                 null, 
    //                 'A1', 
    //                 false
    //             );
    //         },
    //     ];
    // }





