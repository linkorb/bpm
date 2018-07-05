<?php

use Bpm\Loader\BonitaProcessLoader;

require_once "common.php";

$filename = __DIR__ . '/process-design.xml';

$loader = new BonitaProcessLoader();
$process = $loader->loadFile($filename);
print_r($process);


echo "ACTORS:\n";
foreach ($process->getActors() as $element) {
    echo ' - ' . $element->getId() . ': ' . $element->getName();
    if ($element->getIsInitiator()) {
        echo " (initiator)";
    }
    echo "\n";
}
echo "\n";

echo "TASKS:\n";
foreach ($process->getTasks() as $element) {
    echo ' - ' . $element->getId() . ': ' . $element->getName() . "\n";
    //echo ' - ' . $element->getDescription() . "\n";
}
echo "\n";

echo "TRANSITIONS:\n";
foreach ($process->getTransitions() as $element) {
    echo ' - ' . $element->getId() . ': ' . $element->getName() . "\n";
}
