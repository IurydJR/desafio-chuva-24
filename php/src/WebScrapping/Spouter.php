<?php

namespace Chuva\Php\WebScrapping;



use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class Spouter {

    public function spouter ($data) {

        $maxAuthor = $data[0];
        $papers = $data[1];


        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile(__DIR__ .'/../../assets/papers_'. date("d-m-Y") .'.xlsx'); 

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
        $singleRow = WriterEntityFactory::createRow($cellsTitle);
        $writer->addRow($singleRow); 

        foreach ($papers as $article) {
            $cells = [];
            $cells = [
                WriterEntityFactory::createCell($article->id),
                WriterEntityFactory::createCell($article->title),
                WriterEntityFactory::createCell($article->type),
            ];
            $authors = $article->authors;
            $numAuthor = count($authors);;
            foreach ($authors as $author) {
                $cells[]= WriterEntityFactory::createCell($author->name);
                $cells[]= WriterEntityFactory::createCell($author->institution);
            }
            
            $singleRow = WriterEntityFactory::createRow($cells);
            $writer->addRow($singleRow);
        }
        $writer->close();

    }
}