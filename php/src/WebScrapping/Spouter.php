<?php

namespace Chuva\Php\WebScrapping;

require_once '../../vendor/autoload.php';

use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;

/**
 * Does the xlsx document with paper information.
 */
class Spouter
{

  /**
   * Does the style of the xlsx document.
   */
  private function styles()
  {

    $border = new Border(
      new BorderPart(Border::LEFT, Color::rgb(191, 191, 191), Border::WIDTH_THIN, Border::STYLE_SOLID),
      new BorderPart(Border::RIGHT, Color::rgb(191, 191, 191), Border::WIDTH_THIN, Border::STYLE_SOLID)
    );

    $styleChuva = (new Style())
      ->setFontBold()
      ->setFontItalic()
      ->setFontSize(18)
      ->setFontColor(Color::rgb(6, 1, 56))
      ->setShouldWrapText()
      ->setCellAlignment(CellAlignment::RIGHT);

    $styleInc = (new Style())
      ->setFontBold()
      ->setFontItalic()
      ->setFontSize(18)
      ->setFontColor(Color::rgb(6, 1, 56))
      ->setShouldWrapText()
      ->setCellAlignment(CellAlignment::LEFT);

    $styleHeader = (new Style())
      ->setBackgroundColor(Color::rgb(252, 252, 252))
      ->setFontColor(Color::BLACK)
      ->setFontSize(12)
      ->setFontItalic();

    $styleTitle = (new Style())
      ->setFontBold()
      ->setBackgroundColor(Color::rgb(181, 181, 181))
      ->setFontColor(Color::BLACK)
      ->setFontSize(11);

    $styleLine1 = (new Style())
      ->setBackgroundColor(Color::rgb(227, 227, 227))
      ->setFontSize(10)
      ->setBorder($border);

    $styleLine2 = (new Style())
      ->setBackgroundColor(Color::WHITE)
      ->setFontSize(10)
      ->setBorder($border);

    return [$styleChuva, $styleInc, $styleHeader, $styleTitle, $styleLine1, $styleLine2];
  }

  /**
   * get an unrepeated xlsx name based on today's day.
   */
  private function getFilename()
  {
    $dateToday = date("d-m-Y");
    $directory = __DIR__ . '/../../assets/';
    $basename = 'papers_' . $dateToday;
    $filename = $basename;
    $i = 1;

    while (file_exists($directory . '/' . $filename . '.xlsx')) {
      print_r(file_exists($directory . '/' . $filename . '.xlsx'));
      $filename = $basename . '_' . $i;
      $i++;
    }
    $filename .= '.xlsx';
    print_r($filename);
    return ($filename);
  }

  /**
   * Write the papers info on xlsx document.
   */
  public function __construct($data)
  {
    [$styleChuva, $styleInc, $styleHeader, $styleTitle, $styleLine1, $styleLine2] = $this->styles();
    $maxAuthor = $data[0];
    $papers = $data[1];
    $filename = $this->getFilename();


    $writer = new Writer();

    $writer->openToFile(__DIR__ . '/../../assets/' . $filename);

    $cellsHeader = [
      Cell::fromValue('chuva', $styleChuva),
      Cell::fromValue('inc.', $styleInc),
      Cell::fromValue(''),
      Cell::fromValue('criado em: ' . date("d-m-Y")),
    ];
    $i = 0;
    while ($i < $maxAuthor * 2 - 1) {
      $cellsHeader[] = Cell::fromValue('');
      $i += 1;
    }

    $rowHeader = new Row($cellsHeader, $styleHeader);
    $writer->addRow($rowHeader);

    $cellsTitle = [
      Cell::fromValue('id'),
      Cell::fromValue('Title'),
      Cell::fromValue('Type'),
    ];
    $i = 1;
    while ($i <= $maxAuthor) {
      $cellsTitle[] = Cell::fromValue('autor ' . $i);
      $cellsTitle[] = Cell::fromValue('instituição ' . $i);
      $i += 1;
    }

    $rowTitle = new Row($cellsTitle, $styleTitle);
    $writer->addRow($rowTitle);

    $i = 0;
    foreach ($papers as $article) {
      $cells = [
        Cell::fromValue($article->id),
        Cell::fromValue($article->title),
        Cell::fromValue($article->type),
      ];
      $authors = $article->authors;
      $numAuthor = count($authors);
      foreach ($authors as $author) {
        $cells[] = Cell::fromValue($author->name);
        $cells[] = Cell::fromValue($author->institution);
      }

      $j = 0;
      while (++$j <= 2 * ($maxAuthor - $numAuthor)) {
        $cells[] = Cell::fromValue(' ');
      }

      $i = 1 - $i;
      $styleLine = $i == 0 ? $styleLine1 : $styleLine2;
      $row = new Row($cells, $styleLine);
      $writer->addRow($row);
    }
    $writer->close();
  }
}
