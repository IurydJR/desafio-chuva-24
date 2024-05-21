<?php

namespace Chuva\Php\WebScrapping;

require_once '../../../vendor/autoload.php';

use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

/**
 * Does the xslx document with paper information.
 */
class Spouter {

  /**
   * Does the style of the xlsx document.
   */
  private function styles() {

    $border = (new BorderBuilder())
      ->setBorderRight('bfbfbf', Border::WIDTH_THIN, Border::STYLE_SOLID)
      ->setBorderLeft('bfbfbf', Border::WIDTH_THIN, Border::STYLE_SOLID)
      ->build();

    $styleChuva = (new StyleBuilder())
      ->setFontBold()
      ->setFontItalic()
      ->setFontSize(18)
      ->setFontColor('060138')
      ->setShouldWrapText()
      ->setCellAlignment(CellAlignment::RIGHT)
      ->build();

    $styleInc = (new StyleBuilder())
      ->setFontBold()
      ->setFontItalic()
      ->setFontSize(18)
      ->setFontColor('060138')
      ->setShouldWrapText()
      ->setCellAlignment(CellAlignment::LEFT)
      ->build();

    $styleHeader = (new StyleBuilder())
      ->setBackgroundColor('fcfcfc')
      ->setFontColor(Color::BLACK)
      ->setFontSize(12)
      ->setFontItalic()
      ->build();

    $styleTitle = (new StyleBuilder())
      ->setFontBold()
      ->setBackgroundColor('b5b5b5')
      ->setFontColor(Color::BLACK)
      ->setFontSize(11)
      ->build();

    $styleLine1 = (new StyleBuilder())
      ->setBackgroundColor('e3e3e3')
      ->setFontSize(10)
      ->setBorder($border)
      ->build();

    $styleLine2 = (new StyleBuilder())
      ->setBackgroundColor(Color::WHITE)
      ->setFontSize(10)
      ->setBorder($border)
      ->build();

    return [$styleChuva, $styleInc, $styleHeader, $styleTitle, $styleLine1, $styleLine2];
  }

  /**
   * Write the papers info on xlsx document.
   */
  public function __construct($data) {
    [$styleChuva, $styleInc, $styleHeader, $styleTitle, $styleLine1, $styleLine2] = $this->styles();
    $maxAuthor = $data[0];
    $papers = $data[1];

    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile(__DIR__ . '/../../assets/papers_' . date("d-m-Y") . '.xlsx');

    $cellsHeader = [
      WriterEntityFactory::createCell('chuva', $styleChuva),
      WriterEntityFactory::createCell('inc.', $styleInc),
      WriterEntityFactory::createCell(''),
      WriterEntityFactory::createCell('criado em: ' . date("d-m-Y")),
    ];
    $i = 0;
    while ($i < $maxAuthor * 2 - 1) {
      $cellsHeader[] = WriterEntityFactory::createCell('');
      $i += 1;
    };

    $cellsTitle = [
      WriterEntityFactory::createCell('id'),
      WriterEntityFactory::createCell('Title'),
      WriterEntityFactory::createCell('Type'),
    ];
    $i = 1;
    while ($i <= $maxAuthor) {
      $cellsTitle[] = WriterEntityFactory::createCell('autor ' . $i);
      $cellsTitle[] = WriterEntityFactory::createCell('instituição ' . $i);

      $i += 1;
    }
    $multipleRows = [
      WriterEntityFactory::createRow($cellsHeader, $styleHeader),
      WriterEntityFactory::createRow($cellsTitle, $styleTitle),
    ];
    $writer->addRows($multipleRows);

    $i = 0;
    foreach ($papers as $article) {
      $cells = [];
      $cells = [
        WriterEntityFactory::createCell($article->id),
        WriterEntityFactory::createCell($article->title),
        WriterEntityFactory::createCell($article->type),
      ];
      $authors = $article->authors;
      $numAuthor = count($authors);
      foreach ($authors as $author) {
        $cells[] = WriterEntityFactory::createCell($author->name);
        $cells[] = WriterEntityFactory::createCell($author->institution);
      }

      $j = 0;
      while (++$j <= 2 * ($maxAuthor - $numAuthor)) {
        $cells[] = WriterEntityFactory::createCell(' ');
      }

      $i = 1 - $i;
      $styleLine = $i == 0 ? $styleLine1 : $styleLine2;
      $singleRow = WriterEntityFactory::createRow($cells, $styleLine);
      $writer->addRow($singleRow);
    }
    $writer->close();
  }

}
