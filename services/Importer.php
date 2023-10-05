<?php

namespace app\services;

use PhpOffice\PhpSpreadsheet\IOFactory;

class Importer
{
    private string $lastUrl = '';

    private function getRows($worksheet): array
    {
        $rows = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // This loops through all cells,
            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }
            $rows[] = $cells;
        }
        return $rows;
    }

    private function insertRowsIntoTable(array $insertData, string $tabName): void
    {
        foreach ($insertData as $row) {
            if ($row[1] === 'URL') {
                $this->lastUrl = $row[2];
            }
            if ($this->isRowAllowed($row)) {
                DB::insert('dl_xlsx_data', $this->transformRow($row, $tabName));
            }
        }
    }

    private function isRowAllowed(array $row): bool
    {
        return str_contains($row[2], 'dataLayer.push');
    }

    private function transformRow(array $row, string $tabName): array
    {
        return [
            'tab_name' => $tabName,
            'url' => $this->lastUrl,
            'col0' => $row[0],
            'col1' => $row[1],
            'col2' => $row[2],
            'col3' => $row[3],
            'col4' => $row[4],
            'col5' => $row[5],
        ];
    }

    public function importFile($filename): void
    {
        $filetype = IOFactory::identify($filename);
        $reader = IOFactory::createReader($filetype);
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);
        $spreadsheet = $reader->load($filename);

        for ($i = 0; $i < $spreadsheet->getSheetCount(); $i++) {
            $spreadsheet->setActiveSheetIndex($i);
            $activeSheet = $spreadsheet->getActiveSheet();
            $rows = $this->getRows($activeSheet);
            $tabName = $activeSheet->getTitle();
            //var_dump($rows);

            $this->insertRowsIntoTable($rows, $tabName);
        }
    }
}
