<?php

namespace Chuva\Php\WebScrapping\Entity;

/**
 * Paper Author personal information.
 */
class Person {

  /**
   * Person name.
   */
  public string $name;

  /**
   * Person institution.
   */
  public string $institution;

  /**
   * Person institution.
   */
  public ?int $numArticlesPublished;

  /**
   * Builder.
   */
  public function __construct($name, $institution, ?int $numArticlesPublished=null) {
    $this->name = $name;
    $this->institution = $institution;
    $this->numArticlesPublished = $numArticlesPublished;
  }

  public function setNumArticlesPublished($numArticlesPublished) {
    $this->numArticlesPublished = $numArticlesPublished;
  }
}
