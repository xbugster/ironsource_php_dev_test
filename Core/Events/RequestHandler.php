<?php
/**
 * @desc    Initialize event.
 * @author  Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Core\Events;

use Core\Http\Request;

class RequestHandler implements \SplObserver
{
    /**
     * @desc Simply checking if CORS OPTIONS arrived
     *       and then instantiates Request object, who is in his turn aggregates & encapsulates request data
     *       during dispatch, Request object will be send to controller and further accessed in controller using
     *       $this->getRequest()
     *
     * @param \SplSubject|\Core\App $subject
     */
    public function update(\SplSubject $subject) : void
    {
        if ($subject->is_halted()) {
            return;
        }
        $this->_satisfyCORSOptionsIfNecessary($subject);
        $subject->getRegistry()->set('request', new Request());
    }

    /**
     * @desc just check if it is OPTIONS knocking on the door, if so - flag request to not send contents to browser.
     * @param \SplSubject|\Core\App $subject
     */
    private function _satisfyCORSOptionsIfNecessary(\SplSubject $subject) : void
    {
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
            $subject->halt();
            $subject->dontSendContentInResponse();
        }
    }
}