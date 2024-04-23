<?php

namespace Chuva\Php\WebScrapping;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

/**
 * Runner for the Webscrapping exercice.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $html = file_get_contents(__DIR__ . '/../../assets/origin.html');

    @$dom->loadHTML($html);

    $data = (new Scrapper())->scrap($dom);
    $writer = (new WriterEntityFactory())->createXLSXWriter();
    $writer->openToFile('planilhaProceedings.xlsx');

    $cabecalho = ['ID', 'TITLE', 'TYPE'];
    for ($i = 1; $i <= 10; $i++) {
      $cabecalho[] = 'Author ' . $i;
      $cabecalho[] = 'Author instituition ' . $i;
    }

    $rowFromValues = (new WriterEntityFactory())->createRowFromArray($cabecalho);
    $writer->addRow($rowFromValues);

    $i = 0;
    foreach ($data as $item) {
      $result = [];
      foreach ($item['authors'] as $author) {
        $result[$i] = $author['name'];
        $i++;
        $result[$i] = $author['institution'];
        $i++;
      }
      $i = 0;
      $item['authors'] = $result;

      $result = [];
      foreach ($item as $key => $value) {
        if ($key === 'authors') {
          foreach ($value as $author) {
            $result[] = $author;
          }
        }
        else {
          $result[] = $value;
        }
      }
      $insert = (new WriterEntityFactory())->createRowFromArray($result);
      $writer->addRow($insert);
    }
    $writer->close();

  }

}
