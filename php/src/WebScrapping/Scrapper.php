<?php

namespace Chuva\Php\WebScrapping;

require_once './Entity/Paper.php';
require_once './Entity/Person.php';

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array{

    // Declaracao de objeto
    $xpath = new \DOMXPath($dom);

    // Captura a div com a class especificada
    $paperCard = $xpath->query("//a[contains(@class, 'paper-card')]");
    $count = 0;

    $informacoes = array();

    foreach ($paperCard as $papel) {
      // Capturar a div com classe "volume-info"
      $id = $xpath->query("//div[contains(@class, 'volume-info')]")->item($count);
      $id = $id->nodeValue;

      // Capturar a h4 com classe "paper-title"
      $title = $xpath->query("//h4[contains(@class, 'paper-title')]")->item($count);
      $title = $title->nodeValue;

      // Capturar a div com classe "tags mr-sm"
      $type = $xpath->query("//div[contains(@class, 'tags mr-sm')]")->item($count);
      $type = $type->nodeValue;

      // Capturar a span dentro da div 'authors'
      $span = $papel->getElementsByTagName('span');

      $authores = array();

      foreach ($span as $author) {
        $name = $author->nodeValue;
        $institution = $author->getAttribute('title');
        $authores[] =  get_object_vars(new Person($name, $institution));
      }
      $count++;

      $informacoes [] = get_object_vars(new Paper($id, $title, $type, $authores));
    }
    return $informacoes;
  }     
}
