<?php

namespace Chuva\Php\WebScrapping;

use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;


/**
 * Runner for the Webscrapping exercice.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    $data = (new Scrapper())->scrap($dom);



    // Write your logic to save the output file bellow.
    $path = __DIR__ . '/../../assets/modelModf.xlsx';
    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile($path);


    $writer->addRow(WriterEntityFactory::createRowFromArray(["Id", "Title", "Type", "Author 1", "Author 1 Institution",
       "Author 2", "Author 2 Institution", "Author 3",
        "Author 3 Institution", "Author 4", "Author 4 Institution",
        "Author 5", "Author 5 Institution", "Author 6", "Author 6 Institution"
      ], (new StyleBuilder())
      ->setFontSize(12)
      ->setShouldWrapText()
      ->setShouldShrinkToFit()
      ->build()));


    $cells = [];
    foreach($data as $paper){
      $row = [];
      $row[] = $paper->id;
      $row[] = $paper->title;
      $row[] = $paper->type;
      
      foreach($paper->authors as $author){
        $row[] = $author->name;
        $row[] = $author->institution;
      }
      
      $cells[] = $row;
    }

    foreach($cells as $cell){
      $rowFromValues = WriterEntityFactory::createRowFromArray($cell,(new StyleBuilder())
      ->setFontSize(10)
      ->setShouldWrapText()
      ->build());
      $writer->addRow($rowFromValues);
    }

    $writer->close();
   }

}
