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

        $articleLink = $article->getAttribute('href');

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

        $domPaper = new \DOMDocument('1.0', 'utf-8');
        @$domPaper->loadHTML($this->connection($articleLink));

        if ($type == "Poster Presentation") {
          $authorUrl = $this->getAuthorUrlByPosterPresentationHtml($domPaper);
        }
        elseif ($type == "Invited Lecturer") {
          $authorUrl = $this->getAuthorUrlByInvitedLectureHtml($domPaper);
        }

        if (!$authorUrl == NULL) {
          $domAuthor = new \DOMDocument('1.0', 'utf-8');
          @$domAuthor->loadHTML($this->connection($authorUrl));

          $numArticles = $this->authorScrapper($domAuthor);
          $persons[0]->setNumArticlesPublished($numArticles);
        }
        $papers[] = new Paper($id, $title, $type, $persons);
      }
    }

    return [$maxAuthor, $papers];
  }

  /**
   * Makes a file by html page from an url.
   */
  public function connection($url) {
    $options = [
      'http' => [
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n",
      ],
    ];
    $context = stream_context_create($options);
    $htmlContent = @file_get_contents($url, FALSE, $context);

    if ($htmlContent === FALSE) {
      print_r("Erro ao carregar o conteúdo do site ou site inacessível");
    }

    return $htmlContent;
  }

  /**
   * Loads author url from the HTML by poster Presentation and returns the data.
   */
  public function getAuthorUrlByPosterPresentationHtml(\DOMDocument $dom) {
    $divElements = $dom->getElementsByTagName('div');
    $authorLink = '';

    foreach ($divElements as $divElement) {
      if ($divElement->getAttribute('class') === 'authors-wrapper') {
        $authors = $divElement->getElementsByTagName('div')->item(0);
        $author = $authors->getElementsByTagName('li')->item(0);
        $authorLink = 'https://proceedings.science' . $author->getElementsByTagName('a')->item(0)->getAttribute('href');
      }
    }

    return $authorLink;
  }

  /**
   * Loads author url from the HTML by Invited Lecture and returns the data.
   */
  public function getAuthorUrlByInvitedLectureHtml(\DOMDocument $dom) {
    $divElements = $dom->getElementsByTagName('div');
    $authorLink = '';

    foreach ($divElements as $divElement) {
      if ($divElement->getAttribute('class') === 'region region-title-area') {
        $authors = $divElement->getElementsByTagName('div')->item(0);
        $author = $authors->getElementsByTagName('li')->item(0);
        $authorLink = 'https://proceedings.science' . $author->getElementsByTagName('a')->item(0)->getAttribute('href');
      }
    }
    return $authorLink;
  }

  /**
   * Loads author information from the HTML and returns the number or articles.
   */
  public function authorScrapper(\DOMDocument $dom) {

    $numArticles = $dom->getElementsByTagName('span')->item(4)->textContent;
    return $numArticles;
  }

}
