<?php

namespace Chuva\Php\WebScrapping;

require_once __DIR__ . '/Entity/Paper.php';
require_once __DIR__ . '/Entity/Person.php';

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

    $xpath = new \DOMXPath($dom);

    $paperCard = $xpath->query("//a[contains(@class, 'paper-card')]");
    $count = 0;
    $informacoes = [];

    foreach ($paperCard as $papel) {
      $id = $xpath->query("//div[contains(@class, 'volume-info')]")->item($count);
      $id = $id->nodeValue;

      $title = $xpath->query("//h4[contains(@class, 'paper-title')]")->item($count);
      $title = $title->nodeValue;

      $type = $xpath->query("//div[contains(@class, 'tags mr-sm')]")->item($count);
      $type = $type->nodeValue;

      $span = $papel->getElementsByTagName('span');

      $authores = [];

      foreach ($span as $author) {
        $name = $author->nodeValue;
        $institution = $author->getAttribute('title');
        $authores[] = get_object_vars(new Person($name, $institution));
      }
      $count++;

      $informacoes[] = get_object_vars(new Paper($id, $title, $type, $authores));
    }

    return $informacoes;
  }

}
