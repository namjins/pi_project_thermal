<?php
/**
 * Created by PhpStorm.
 * User: Marc-PC
 * Date: 4/30/2018
 * Time: 6:02 PM
 */

class Porch
{
    /**
     * @var int
     */
    private $last_movement;

    /**
     * @var int
     */
    private $active_start;

    /**
     * @var int
     */
    private $active_stop;

    /**
     * @var int
     */
    private $activation_count;

    /**
     * @var int
     */
    private $activation_threshold;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @var string
     */
    private $audio_scream;


    /**
     * Porch constructor.
     */
    public function __construct(DateTime $active_start, Datetime $active_stop, $timeout, $activation_threshold)
    {
        $this->last_movement = time();
        $this->active_start = date("G", strtotime($active_start));
        $this->active_stop = date("G", strtotime($active_stop));
        $this->activation_threshold = $activation_threshold;
        $this->activation_count = 0;
        $this->timeout = $timeout;
        $this->audio_scream = `omxplayer -o local audio/nmh_scream1.mp3`;
    }

    /**
     * Call this function whenever there is motion.
     *
     * @return void
     */
    public function motionDetected()
    {
        $this->increaseActivationCount();
        $new_movement = time();

        $last_movement = $this->getHour($this->last_movement);
        // Check that we're within our time window
        if ($last_movement > $this->active_start && $last_movement < $this->active_stop) {
            if ((($new_movement - $this->last_movement) > $this->timeout || $this->activation_count <= $this->activation_threshold)) {
                $this->activateSound();
            }
        }
        $this->last_movement = $new_movement;
    }

    /**
     * Call this to reset $activation_threshold
     *
     * @return int
     */
    private function getHour($timestamp)
    {
        return date("G", new DateTime($timestamp));

    }

    /**
     * Call this to reset $activation_threshold
     *
     * @return void
     */
    public function resetActivationCount()
    {
        if (time() - $this->last_movement >= $this->timeout) {
            $this->activation_count = 0;
        }
    }

    /**
     * Call this function whenever there is motion.
     *
     * @return void
     */
    private function increaseActivationCount()
    {
        $this->activation_count++;
    }

    /**
     * Call this function when playing sound
     *
     * @return void
     */
    private function activateSound()
    {
        echo $this->audio_scream;
    }
}