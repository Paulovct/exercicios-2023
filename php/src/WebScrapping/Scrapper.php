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
    $data = [];
    $elements = $dom->getElementsByTagName("a");
    $cards = [];
    foreach ($elements as $element) {
      if (str_contains($element->getAttribute("class"), "paper-card")) {
        $cards[] = $element;
      }
    }
    foreach ($cards as $card) {
      $authors = [];
      foreach ($card->firstElementChild->nextSibling->getElementsByTagName("span") as $element) {
        $authors[] = new Person(
          explode(";", $element->textContent)[0],
          $element->getAttribute("title")
        );
      }
      $data[] = new Paper(
        $card->lastElementChild->lastElementChild->textContent,
        $card->firstElementChild->textContent,
        $card->lastElementChild->firstElementChild->textContent,
        $authors
      );
    }
    return $data;
  }

}
