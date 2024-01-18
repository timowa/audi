<?php

namespace App\Console\Commands;

use App\Jobs\Models\ParseFromHtmlJob;
use App\Models\CarModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class parseModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audi:models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получить все модели ауди с дрома и записать их в бд';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://www.drom.ru/catalog/audi/';
        dispatch(new ParseFromHtmlJob($url));
    }


}
