<?php

// Compatibility entry point for the stage-2 CLI scripts.
require_once dirname(__DIR__, 2).'/app/Services/Analytics/SalidasDataset.php';
if (! class_exists('SalidasDataset', false)) {
    class_alias(\App\Services\Analytics\SalidasDataset::class, 'SalidasDataset');
}
