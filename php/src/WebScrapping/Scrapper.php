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
          $persons[] = new Person($author,$institute);
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
        $papers[] = new Paper($id,$title,$type,$persons);
      }
    }


    return [$maxAuthor, $papers];
  }

}
