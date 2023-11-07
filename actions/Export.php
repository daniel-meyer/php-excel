<?php

namespace app\actions;

use app\services\Exporter;

class Export
{
    private const FILENAME = 'dl-tracker-config.json';

    public function run(): void
    {
        $importer = new Exporter();
        if ($importer->exportToFile(self::FILENAME)) {
            echo sprintf('<div class="alert alert-success"> File %s exported successfully</div>', self::FILENAME);
        } else {
            echo '<div class="alert alert-danger">Export failed</div>';
        }
    }
}
