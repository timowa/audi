<?php

namespace App\Jobs\Generations;

use App\Models\Generation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ParseGenerationsFromHtml implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $url;

    public function __construct($id,$url)
    {
        $this->id = $id;
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Generation::truncate();
        $url = $this->url;
        $html = Http::get($url);
        $html =  substr($html->body(), strpos($html->body(),'<body'));
        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML("\xEF\xBB\xBF" .$html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_use_internal_errors(false);
        $xpath = new \DOMXPath($dom);
        $newDom = new \DOMDocument;
        $newDom->formatOutput = true;
        $divs = $xpath->query('//div[@data-app-root="catalog-generations-page"]//div[contains(concat(" ", normalize-space(@class), " "), "css-pyemnz")]');
        $i = 0;
        while( $myItem = $divs->item($i++) ){
            $node = $newDom->importNode( $myItem, true );
            $children = $this->returnOnlyDivChildren($node);
            $region=$children->item(0)->attributes->getNamedItem('id')->value;
            $generations = $this->returnOnlyDivChildren($children->item(1));
            foreach ($generations as $generation){
                $link = $this->url;
                $link .= $generation->getElementsByTagName('a')->item(0)->getAttribute('href');
                $img = $generation->getElementsByTagName('img')->item(0)->getAttribute('data-src');
                $span = preg_split('/\r\n|\n|\r/',$generation->getElementsByTagName('span')->item(0)->nodeValue);
                $name = $span[0];
                if(strpos($name,'}')>-1){
                    $name = substr($name,strpos($name,'}')+1);
                }
                $period = $span[1];
                $generationNumber = $this->returnOnlyDivChildren($generation->getElementsByTagName('a')->item(0))->item(2)->nodeValue[0];

                Generation::create(
                    ['name'=>$name,
                        'model_id'=>$this->id,
                        'period'=>$period,
                        'generation'=>$generationNumber,
                        'market'=>$region,
                        'link'=>$link,
                        'pictureUrl'=>$img]
                );
            }
        }
    }

    function returnOnlyDivChildren($parent){
        $children = $parent->childNodes;
        $nb = $children->length;
        for($pos=0; $pos<$nb; $pos++){
            if($children->item($pos)!= null && $children->item($pos)->nodeName!='div')
                $parent->removeChild($children->item($pos));
        }
        return $children;
    }
}
