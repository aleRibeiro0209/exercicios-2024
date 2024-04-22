<?php

namespace Chuva\Php\WebScrapping;

require_once 'Scrapper.php';
require_once '../vendor/box/spout/src/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

/**
 * Runner for the Webscrapping exercice.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $file = 'planilhaProceedings.xlsx';
    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToBrowser($file);

    $cabecalho = ['ID', 'Title', 'Type', 'Author 1', 'Author 1 Institution', 'Author 2', 'Author 2 Institution', 'Author 3', 'Author 3 Institution', 'Author 4', 'Author 4 Institution', 'Author 5', 'Author 5 Institution', 'Author 6', 'Author 6 Institution', 'Author 7', 'Author 7 Institution', 'Author 8', 'Author 8 Institution', 'Author 9', 'Author 9 Institution','Author 10', 'Author 10 Institution'];

    $rowFromValues = WriterEntityFactory::createRowFromArray($cabecalho);
    $writer->addRow($rowFromValues); 

    $dom = new \DOMDocument('1.0', 'utf-8');
    $html = file_get_contents(__DIR__ . '/../../assets/origin.html');

    @$dom->loadHTML($html);

    $data = (new Scrapper())->scrap($dom);    

    $i=0;
    foreach ($data as $item) {
      $result = [];
      foreach ($item['authors'] as $author) {
        $result[$i] = $author['name'];
        $i++;
        $result[$i] = $author['institution'];
        $i++;
      }
      $i=0;
      $item['authors'] = $result;

      $result = [];
      foreach ($item as $key => $value) {
          if ($key === 'authors') {
              foreach ($value as $author) {
                $result[] = $author;
              }
          } else {
              $result[] = $value;
          }
      }
      $insert = WriterEntityFactory::createRowFromArray($result);
      $writer->addRow($insert);
    }
    $writer->close();
  }
}