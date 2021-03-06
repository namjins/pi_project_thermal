<?php
// Original idea and code: https://github.com/colinodell/php-iot-examples
date_default_timezone_set('America/Denver');

const GPIO_PIN = 17;
const TIMEOUT = 60 * 5; // 5 minutes
const ACTIVATION_THRESHOLD = 2;


require_once 'vendor/autoload.php';
include "src/Porch.php";

use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\Pin\InputPinInterface;

$active_start = new DateTime('2000-01-01 17:00:00');
$active_stop = new DateTime('2000-01-01 06:00:00');

$porch = new Porch($active_start, $active_stop, TIMEOUT, ACTIVATION_THRESHOLD);
$gpio = new GPIO();
$pin = $gpio->getInputPin(GPIO_PIN);
$pin->setEdge(InputPinInterface::EDGE_RISING);

$interruptWatcher = $gpio->createWatcher();
$interruptWatcher->register($pin, function (InputPinInterface $pin, $value) use ($porch) {
    $porch->motionDetected();
});

while (true) {
    // Keeps watcher active
    $interruptWatcher->watch(TIMEOUT * 1000);
    $porch->resetActivationCount();

}
?>
