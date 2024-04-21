<?php

namespace Chuva\Php\WebScrapping;

require_once 'Scrapper.php';
require_once '../vendor/box/spout/src/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Chuva\Php\WebScrapping\Scrapper;
/**
 * Runner for the Webscrapping exercice.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    // Criando o arquivo .XLSX similar ao model.xlsx
    $file = 'planilhaProceedings.xlsx';
    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToBrowser($file);

    // Escrevendo cabecalho
    $cabecalho = ['ID', 'Title', 'Type', 'Author 1', 'Author 1 Institution', 'Author 2', 'Author 2 Institution', 'Author 3', 'Author 3 Institution', 'Author 4', 'Author 4 Institution', 'Author 5', 'Author 5 Institution', 'Author 6', 'Author 6 Institution', 'Author 7', 'Author 7 Institution', 'Author 8', 'Author 8 Institution', 'Author 9', 'Author 9 Institution','Author 10', 'Author 10 Institution'];

    // Adiciona uma nova linha com os valores
    $rowFromValues = WriterEntityFactory::createRowFromArray($cabecalho);
    $writer->addRow($rowFromValues); 

    // Utilizando DOMDocument para acessar o conteudo da pagina
    $dom = new \DOMDocument('1.0', 'utf-8');
    $html = file_get_contents(__DIR__ . '/../../assets/origin.html');
    
    // Carregando o HTML da pagina
    @$dom->loadHTML($html);

    $data = (new Scrapper())->scrap($dom);    

    $i=0;
    foreach ($data as $item) {
      // Array sequencial resultante
      $result = array();
      foreach ($item['authors'] as $author) {
        $result[$i] = $author['name'];
        $i++;
        $result[$i] = $author['institution'];
        $i++;
      }
      $i=0;
      $item['authors'] = $result;

      $result = array();
      foreach ($item as $key => $value) {
          if ($key === 'authors') {
              foreach ($value as $author) {
                $result[] = $author;
              }
          } else {
              $result[] = $value;
          }
      }
      // Criando uma única linha para adicionar a na planilha
      $insert = WriterEntityFactory::createRowFromArray($result);
      $writer->addRow($insert);
    }

    $writer->close();
  }
}