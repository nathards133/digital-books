<?php

namespace App\Console\Commands;

use App\Models\Elivros;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class downloadElivros extends Command
{
    protected $signature = 'download:livros';

    protected $description = 'Download Livros digitais do site https://lelivros.love/';

    public function handle()
    {
        $this->crawName = 'Livros Digitais';
        $this->model = Elivros::class;
        $this->tag = 'T';

        $this->parseLinks();
    }

    public function parseLinks(): void
    {
        $this->info("Buscando links...");

        $response = Http::get('https://lelivros.love/page/1');
        $body = $response->body();
        $crawler = new Crawler($body);
        $paginacao = $crawler->filter('.wp-pagenavi a')->eq(0)->text();
        $paginacao = (int)$paginacao;
        for ($pagina = 1; $pagina < $paginacao; $pagina++) {
            $this->buscarPaginas($pagina);
        }

    }
    public function buscarPaginas($pagina)
    {
        $this->info('Pagina ' . $pagina);
        $response = Http::get('https://lelivros.love/page/' . $pagina);
        $body = $response->body();
        $crawler = new Crawler($body);

    }
}
