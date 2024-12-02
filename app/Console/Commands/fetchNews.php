<?php

namespace App\Console\Commands;

use App\Services\NewsSourceContext;
use Illuminate\Console\Command;

class fetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the latest news articles from external APIs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $services = collect(config('news.sources'));

        $enabledServices = $services->where('enabled',true)->all();

        foreach($enabledServices as $key => $service){
            $newsSource = new NewsSourceContext();
    
            $newsSource->setNewsSource(app($service['service']));
            $newsSource->fetch();
        }

    }
}
