<?php

include_once 'config.php';
require 'library/vendor/autoload.php';

use PhpGpio\Gpio;

/**
 * Open during DELAY_TO_OPEN_THE_VALVE seconds the valve, then close it.
 * Return false if errors occurred, true else.
 *
 * @return bool
 */
function openThenCloseTheValve()
{
    $isOk = false;

    // Initialize the pin :
    $gpio = new GPIO();
    $isOkSetup = $gpio->setup(INTERRUPTOR_PIN_NUMERO, "out");
    if ($isOkSetup !== false) {
        // Open the valve :
        $isOkOutPutOne = $gpio->output(INTERRUPTOR_PIN_NUMERO, 1);
        if ($isOkOutPutOne !== false) {
            // Wait during the delay :
            $isOkSleep = sleep(DELAY_TO_OPEN_THE_VALVE);
            if ($isOkSleep !== false) {
                // Close the valve :
                $isOkOutPutZero = $gpio->output(INTERRUPTOR_PIN_NUMERO, 0);
                if ($isOkOutPutZero !== false) {
                    $isOkUnexport = $gpio->unexportAll();
                    if ($isOkUnexport !== false) {
                        $isOk = true;
                    } else {
                        sendNotification('Cannot unexport the pin numero ' . INTERRUPTOR_PIN_NUMERO);
                    }
                } else {
                    sendNotification('Cannot close the pin numero ' . INTERRUPTOR_PIN_NUMERO);
                }
            } else {
                sendNotification('Cannot sleep for ' . DELAY_TO_OPEN_THE_VALVE . ' seconds');
            }
        } else {
            sendNotification('Cannot open the pin numero ' . INTERRUPTOR_PIN_NUMERO);
        }
    } else {
        sendNotification('Cannot initialize the pin numero ' . INTERRUPTOR_PIN_NUMERO);
    }

    return $isOk;
}


/**
 * Send notification in terminal and by email.
 */
function sendNotification($message)
{
    var_dump($message);
}

openThenCloseTheValve();
