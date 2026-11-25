<?php

use Illuminate\Foundation\Inspiring;
use App\Models\PodDocument;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



// uncomment below  and adjust time when ready to schedule pod indexing into database

// Schedule::command('pod:index')->dailyAt('23:00')->withoutOverlapping();





// uncomment below and adjust time when ready to implement retention policy of one year for pod records

// Schedule::command('model:prune', [
//     '--model' => [PodDocument::class],
//     '--chunk' => 250, 
// ])->dailyAt('02:00');
