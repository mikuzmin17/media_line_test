<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DiDom\Document;
use Illuminate\Support\Facades\Http;
use function PHPUnit\Framework\isEmpty;

class ProcessParsing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Спарсить (программно) первые 15 новостей с rbk.ru
        $response = Http::get('https://www.rbc.ru');
        $document = new Document($response->body());
        $links = $document->find('.news-feed__item.js-news-feed-item.js-yandex-counter');
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            $this->parserPost($href);
        }
    }

    /**
     * Подготовим данные для записи в базу данных
     * @param $href
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function parserPost($href)
    {
        $responsePost = Http::get($href);
        $documentPost = new Document($responsePost->body());
        $documentPost = $this->validate($documentPost);
        $header = $this->parserHeader($documentPost);
        $text = $this->parserArticle($documentPost);
        $img = $documentPost->find('img');
        if ($img) var_dump("img");

        // вставить в базу данных (составить структуру самому)
        $post = new Post();
        $post->href = $href;
        $post->title = $header;
        $post->text = $text;
//        $post->img = $img;
        $post->save();
    }

    /**
     * Получим статью
     * @param $documentPost
     * @return string
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function parserArticle($documentPost)
    {
        $articles = $documentPost->find('p');
        if ($articles) {
            $post = '';
            foreach ($articles as $article) {
                $post .= $article->text();
            }
        }
        return $post;
    }

    /**
     * Получим заголовок
     * @param $documentPost
     * @return string
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function parserHeader($documentPost)
    {
        $header = $documentPost->find('h1');
        try {
            return $header[0]->text();
        } catch (\Exception $exception) {
            return $header[0]-> innerHtml();
        }
    }

    /**
     * Если статья партнеров, то переадресация
     * @param $documentPost
     * @return Document
     */
    public function validate($documentPost)
    {
        $iframe = $documentPost->find('h1');
        if (!empty($iframe)) {
            return $documentPost;
        } else {
            $str = $documentPost->find('meta')[0]->getAttribute('content');
            preg_match("/http.*/", $str, $link);
            if ($link) {
                $responsePost = Http::get($link[0]);
                $documentPost = new Document($responsePost->body());
                return $documentPost;
            }
        }
    }
}
