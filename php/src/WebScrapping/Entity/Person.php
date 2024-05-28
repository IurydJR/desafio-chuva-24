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
  public string $numArticlesPublished;

  /**
   * Builder.
   */
  public function __construct($name, $institution, $numArticlesPublished) {
    $this->name = $name;
    $this->institution = $institution;
    $this->numArticlesPublished = $numArticlesPublished;
  }

}
