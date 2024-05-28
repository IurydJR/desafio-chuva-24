<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

require_once 'Entity/Paper.php';
require_once 'Entity/Person.php';

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {

    $papers = [];
    $articles = $dom->getElementsByTagName('a');
    $maxAuthor = 0;

    foreach ($articles as $article) {
      if ($article->getAttribute('class') === 'paper-card p-lg bd-gradient-left') {
        $title = $article->getElementsByTagName("h4")->item(0)->textContent;
        $spanElements = $article->getElementsByTagName("span");

        $numAuthor = $spanElements->length;
        $maxAuthor = $numAuthor > $maxAuthor ? $numAuthor : $maxAuthor;

        $persons = [];
        foreach ($spanElements as $spanElement) {
          $author = $spanElement->textContent;
          $institute = $spanElement->getAttribute('title');
          if (strlen($author) > 2) {
            $persons[] = new Person($author, $institute);
          }
        }

        $divElements = $article->getElementsByTagName("div");
        foreach ($divElements as $divElement) {
          if ($divElement->getAttribute('class') === 'tags mr-sm') {
            $type = $divElement->textContent;
          }
          if ($divElement->getAttribute('class') === 'volume-info') {
            $id = (int) $divElement->textContent;
          }

        }
        $papers[] = new Paper($id, $title, $type, $persons);
      }
    }

    return [$maxAuthor, $papers];
  }

  public function connection($url) {
    $options = [
      'http' => [
      'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n"
      ]
    ];
    $context = stream_context_create($options);

    $htmlContent = @file_get_contents($url, false, $context);

    if ($htmlContent === FALSE) {
      die("Erro ao carregar o conteúdo do site ou site inacessível");
    }      

    return $htmlContent;
  }

  public function userUrl(\DOMDocument $dom) {

    $divElements = $dom->getElementsByTagName('div');
    $authorLink = '';

    foreach ($divElements as $divElement) {
      if ($divElement->getAttribute('class') === 'authors-wrapper') {
        $authors = $divElement->getElementsByTagName('div')->item(0);
        $author = $authors->getElementsByTagName('li')->item(0);
        $authorLink = 'https://proceedings.science' . $author->getElementsByTagName('a')->item(0)->getAttribute('href');

      }
    return $authorLink;
    }
  }

  public function userScrapper(\DOMDocument $dom) {

    $numArticles = $dom->getElementsByTagName('span')->item(4)->textContent;
    return $numArticles;
  }

}
