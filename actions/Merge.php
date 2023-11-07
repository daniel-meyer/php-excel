<?php

namespace app\actions;

use app\services\MergeEvents;

class Merge
{
    public function run(): void
    {
        $service = new MergeEvents();
        $service->process();
        echo '<div class="alert alert-success">Data processed successfully</div>';
    }
}
