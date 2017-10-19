<?php
/**
 * @desc    Application main
 * @author  Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Core;
use Core\Events\Initialize;
use Core\Events\RequestHandler;
use Core\Events\ResponseHandler;
use Core\Events\Router;
use Core\Http\Response;

class App implements \SplSubject
{
    /**
     * @desc observers array
     * @var \SplObjectStorage
     */
    private $_storage = null;

    /**
     * @var Registry
     */
    private $_registry = null;

    /**
     * @var string|null full path to app root directory
     */
    private $_rootPath = null;

    /**
     * @desc response holder for rendering later.
     * @var Response
     */
    private $_response = null;

    /**
     * Internal flag whether to halt the app and show error.
     * Means, if application failed somewhere inside -> we halt and render error 500.
     * @var bool
     */
    private $_halted = false;

    /**
     * Instruction to not render content in response, used only for OPTIONS requests to satisfy CORS.
     * @var bool
     */
    private $_dontSendContent = false;

    /**
     * App constructor.
     * @param array $rootPath full path to application directory.
     */
    public function __construct($rootPath = null) {
        $this->_rootPath = $rootPath;
        $this->_storage = new \SplObjectStorage();
        $this->_setupLifecycleEvents();
        return $this;
    }

    /**
     * @desc attach observer part of spl subject interface
     * @param \SplObserver $observer
     */
    public function attach(\SplObserver $observer) : void {
        $this->_storage->attach( $observer );
    }

    /**
     * @desc detach observer - part of spl subject interface
     * @param \SplObserver $observer
     */
    public function detach(\SplObserver $observer) : void {
        $this->_storage->detach( $observer );
    }

    /**
     * @desc notify all the attached events.
     * @return void
     */
    public function notify() : void {
        foreach($this->_storage as $observer) {
            $observer->update($this);
        }
    }

    /**
     * @desc
     * @param Response $response
     */
    public function setResponse(Response $response) : void {
        $this->_response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse() : Response {
        return $this->_response;
    }

    /**
     * @return null|string
     */
    public function getRootPath() :? string {
        return $this->_rootPath;
    }

    /**
     * @desc get registry instance.
     * @return Registry
     */
    public function getRegistry() : Registry {
        return $this->_registry;
    }

    /**
     * @desc halt application execution
     * @param string $message textual message.
     * @return void
     */
    public function halt($message = 'Something went wrong') : void {
        $this->_halted = true;
        $response = new Response();
        $this->setResponse($response->setJsonResponseType()->setFailure()->setContent($message));
    }

    /**
     * @desc is application halted ?
     * @return bool
     */
    public function is_halted() : bool {
        return $this->_halted === true;
    }

    /**
     * @desc Used to prevent application from rendering anything
     *       Method used for OPTIONS requests to approve ANY ajax.
     *       We don't care for approved headers, auth, etc.
     */
    public function dontSendContentInResponse() : void {
        $this->_dontSendContent = true;
    }

    /**
     * @desc getter of the flag for external instances (observers)
     * @return bool
     */
    public function isNotToSendContent() : bool {
        return $this->_dontSendContent;
    }

    /**
     * @param Registry $registry
     * @return bool
     */
    public function setRegistry(Registry $registry) : bool {
        if (is_null($registry)) {
            return false;
        }
        $this->_registry = $registry;
        return true;
    }

    /**
     * Simple pass through access for readability
     * wrapped into try-cache in order to catch any unexpected exceptions thrown
     * and still finish the execution gracefully without crashing dependent parties.
     * e.g. own apps, partners, etc.
     * @return void
     */
    public function run() : void
    {
       try {
            $this->notify();
       } catch(\Throwable $exception) {
            header('Content-type: application/json; charset=utf-8');
            print '{"success":"false", "data":"Application unexpectedly crashed"}';
       }
    }

    /**
     * @desc life cycle event builder.
     * @return void
     */
    private function _setupLifecycleEvents() : void {
        $this->attach(new Initialize());
        $this->attach(new RequestHandler());
        $this->attach(new Router());
        $this->attach(new ResponseHandler());
    }
}