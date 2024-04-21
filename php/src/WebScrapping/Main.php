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

    // // Escrevendo cabecalho
    $cabecalho = ['ID', 'Title', 'Type', 'Author 1', 'Author 1 Institution', 'Author 2', 'Author 2 Institution', 'Author 3', 'Author 3 Institution', 'Author 4', 'Author 4 Institution', 'Author 5', 'Author 5 Institution', 'Author 6', 'Author 6 Institution', 'Author 7', 'Author 7 Institution', 'Author 8', 'Author 8 Institution', 'Author 9', 'Author 9 Institution','Author 10', 'Author 10 Institution'];

    // Adiciona uma nova linha com os valores
    $rowFromValues = WriterEntityFactory::createRowFromArray($cabecalho);
    $writer->addRow($rowFromValues); 

    // Utilizando DOMDocument para acessar o conteudo da pagina
    $dom = new \DOMDocument('1.0', 'utf-8');
    $html = file_get_contents(__DIR__ . '/../../assets/origin.html');
    
    // Carregando o HTML da pagina
    @$dom->loadHTML($html);

    // Declaracao de objeto
    $xpath = new \DOMXPath($dom);

    // Captura a div com a class especificada
    $paperCard = $xpath->query("//a[contains(@class, 'paper-card')]");
    $count = 0;

    foreach ($paperCard as $papel) {
      $data = (new Scrapper())->scrap($dom, $papel, $count);
      $count++;

      // Declaracao do array que recebera todos os valores na sequencia
      $sequentialArray = [];

      foreach ($data as $key => $value) {
          // Se o valor for um array insere os valores deste array no array sequencial
          if (is_array($value)) {
              foreach ($value as $innerValue) {
                  $sequentialArray[] = $innerValue['name'];
                  $sequentialArray[] = $innerValue['institution'];
              }
          } else {
              // Se não for um array, apenas adiciona o valor no array sequencial
              $sequentialArray[] = $value;
          }
      }

      // Criando uma única linha para adicionar ao escritoro
      $insert = WriterEntityFactory::createRowFromArray($sequentialArray);
      $writer->addRow($insert);
    }

    $writer->close();
  }

}

$teste = new Main();
$teste->run();