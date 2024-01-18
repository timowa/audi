<?php

namespace App\Console\Commands;

use App\Jobs\Generations\ParseGenerationsFromHtml;
use App\Models\CarModel;
use App\Models\Generation;
use Illuminate\Console\Command;

class parseGenerations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audi:generations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсит все поколения всех моделей ауди с дрома';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Generation::truncate();
        $models = CarModel::all();
        foreach ($models as $model){
            dispatch(new ParseGenerationsFromHtml($model->id,$model->url));
        }

    }


}
