<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {
    
    $articles = $dom->getElementsByTagName('a');
    
    foreach ($articles as $article) {
      if ($article->getAttribute('class') === 'paper-card p-lg bd-gradient-left') {
        $title = $article->getElementsByTagName("h4")->item(0)->textContent;
        $spanElements = $article->getElementsByTagName("span");
        
        $data = [];
        foreach ($spanElements as $spanElement) {
          $author = $spanElement->textContent;
          $institute = $spanElement->getAttribute('title');
          $data = [$author,$institute];
        }
      }
    }


    return $data;
  }

}
