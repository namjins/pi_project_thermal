<?php
// Original idea and code: https://github.com/colinodell/php-iot-examples
const GPIO_PIN = 17;

require_once 'vendor/autoload.php';

use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\Pin\InputPinInterface;

$audio_scream = `omxplayer -o local audio/example.mp3`;

$gpio = new GPIO();
$pin = $gpio->getInputPin(GPIO_PIN);
$pin->setEdge(InputPinInterface::EDGE_RISING);

$interruptWatcher = $gpio->createWatcher();
$interruptWatcher->register($pin, function(InputPinInterface $pin, $value) {
	echo $audio_scream;
});

while(true) {

}
?>
