<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EpicorOE_LINE;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Spatie\PdfToText\Pdf;

class EpicorReportController extends Controller
{

	public function ocr() {


	$binaryPath = config('services.poppler.pdftotext_path');
	$path = Storage::path('documents\texttest.pdf');
	$pathubuntu = '/mnt/windowspod/doc26614720260105063713_001.pdf';

	// Quick static usage
	$text = Pdf::getText($pathubuntu, $binaryPath);


	dd($text);

	// Or fluent usage (if adding options like -layout)
	// $text = (new Pdf($binaryPath))
	//     ->setPdf($pdfFilePath)
	//     ->setOptions(['layout'])
	//     ->text();


	}

    


}
