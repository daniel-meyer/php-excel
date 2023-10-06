<?php

namespace app\actions;

use app\services\DLExporter;

class Exporttodl
{
    public function run(): void
    {
        $service = new DLExporter();
        $service->process();
        echo '<div class="alert alert-success">Data processed successfully</div>';
    }
}
