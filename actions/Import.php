<?php

namespace app\actions;

use app\services\Importer;

class Import
{
    public function run(): void
    {
        if ($_FILES["import_excel"]["name"] != '') {
            $this->import();
        } else {
            echo '<div class="alert alert-danger">Please Select File</div>';
        }
    }

    private function import(): void
    {
        $allowed_extension = ['xls', 'csv', 'xlsx'];
        $file_array = explode(".", $_FILES["import_excel"]["name"]);
        $file_extension = end($file_array);

        if (in_array($file_extension, $allowed_extension)) {
            $importer = new Importer();
            $importer->importFile($_FILES['import_excel']['tmp_name']);
            echo '<div class="alert alert-success">Data imported successfully</div>';
        } else {
            echo '<div class="alert alert-danger">Only .xls .csv or .xlsx file allowed</div>';
        }
    }
}


