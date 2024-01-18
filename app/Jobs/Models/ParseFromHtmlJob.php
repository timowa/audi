<?php

namespace App\Jobs\Models;

use App\Models\CarModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ParseFromHtmlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    /**
     * Create a new job instance.
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $html = Http::get($this->url);
        $html =  substr($html->body(), strpos($html->body(),'<body'));
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_use_internal_errors(false);
        $xpath = new \DOMXPath($dom);
        $newDom = new \DOMDocument;
        $newDom->formatOutput = true;
        $divs = $xpath->query('//div[@data-ftid="component_cars-list"]//a');
        $i = 0;
        $links = [];
        while( $myItem = $divs->item($i++) ){
            $node = $newDom->importNode( $myItem, true );
            $newDom->appendChild($node);
            $links[$node->nodeValue] = $node->attributes->item(0)->value;
        }
        foreach ($this->formatNames($links) as $name=>$link){
            $model = CarModel::updateOrCreate(['name'=>$name],['url'=>$link]);
        }
    }

    public function formatNames($links){
        $newLinks = [];
        foreach($links as $name => $link){
            if(strpos($name,'.')>-1){
                $newLinks[(string)substr($name,0,strpos($name,'.'))] = $link;
            }else{
                $newLinks[(string)$name]=$link;
            }
        }
        return $newLinks;
    }
}
