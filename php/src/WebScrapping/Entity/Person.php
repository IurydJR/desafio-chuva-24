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
  public function __construct($name, $institution, ?int $numArticlesPublished = NULL) {
    $this->name = $name;
    $this->institution = $institution;
    $this->numArticlesPublished = $numArticlesPublished;
  }

  /**
   * Set numbe of articles published.
   */
  public function setNumArticlesPublished($numArticlesPublished) {
    $this->numArticlesPublished = $numArticlesPublished;
  }

}
