<?php

namespace app\actions;

use app\services\Importer;

class Import
{
    private const ALLOWED_EXTENSIONS = ['xls', 'csv', 'xlsx'];

    public function run(): void
    {
        if (!$this->import($_FILES["import_excel"]["name"])) {
            echo '<div class="alert alert-danger">Please Select File</div>';
        }
    }

    private function import(string $filename): bool
    {
        if (!$filename) {
            return false;
        }
        if (in_array(pathinfo($filename, PATHINFO_EXTENSION), self::ALLOWED_EXTENSIONS)) {
            $importer = new Importer();
            $importer->importFile($_FILES['import_excel']['tmp_name']);
            echo '<div class="alert alert-success">Data imported successfully</div>';
        } else {
            echo '<div class="alert alert-danger">Only .xls .csv or .xlsx file allowed</div>';
        }
        return true;
    }
}
