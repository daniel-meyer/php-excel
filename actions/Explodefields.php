<?php

namespace app\actions;

use app\services\FieldExploder;

class Explodefields
{
    public function run(): void
    {
        $service = new FieldExploder();
        $service->process();
        echo '<div class="alert alert-success">Data processed successfully</div>';
    }
}


