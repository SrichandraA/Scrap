<?php
namespace Album;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Zend\View\Model\JsonModel;

use Album\Model\Student;
use Album\Model\StudentTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;


 class Module implements AutoloaderProviderInterface, ConfigProviderInterface
 {
   public function onBootstrap(MvcEvent $e)
   {
       $eventManager        = $e->getApplication()->getEventManager();
       $moduleRouteListener = new ModuleRouteListener();
       $moduleRouteListener->attach($eventManager);

       $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 0);
       $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'onRenderError'), 0);
   }

   public function onDispatchError($e)
   {
       return $this->getJsonModelError($e);
   }

   public function onRenderError($e)
   {
       return $this->getJsonModelError($e);
   }

   public function getJsonModelError($e)
   {
       $error = $e->getError();
       if (!$error) {
           return;
       }

       $response = $e->getResponse();
       $exception = $e->getParam('exception');
       $exceptionJson = array();
       if ($exception) {
           $exceptionJson = array(
               'class' => get_class($exception),
               'file' => $exception->getFile(),
               'line' => $exception->getLine(),
               'message' => $exception->getMessage(),
               'stacktrace' => $exception->getTraceAsString()
           );
       }

       $errorJson = array(
           'message'   => 'An error occurred during execution; please try again later.',
           'error'     => $error,
           'exception' => $exceptionJson,
       );
       if ($error == 'error-router-no-match') {
           $errorJson['message'] = 'Resource not found.';
       }

       $model = new JsonModel(array('errors' => array($errorJson)));

       $e->setResult($model);

       return $model;
   }
     public function getAutoloaderConfig()
     {
         return array(
             'Zend\Loader\ClassMapAutoloader' => array(
                 __DIR__ . '/autoload_classmap.php',
             ),
             'Zend\Loader\StandardAutoloader' => array(
                 'namespaces' => array(
                     __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                 ),
             ),
         );
     }

     public function getConfig()
     {
         return include __DIR__ . '/config/module.config.php';
     }
      public function getServiceConfig()
     {
         return array(
             'factories' => array(
                 'Album\Model\AlbumTable' =>  function($sm) {
                     $tableGateway = $sm->get('AlbumTableGateway');
                     $table = new AlbumTable($tableGateway);
                     return $table;
                 },
                 'AlbumTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Album());
                     return new TableGateway('class', $dbAdapter, null, $resultSetPrototype);
                 },
                   'Album\Model\StudentTable' =>  function($sm) {
                     $tableGateway = $sm->get('StudentTableGateway');
                     $table = new StudentTable($tableGateway);
                     return $table;
                 },
                 'StudentTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Student());
                     return new TableGateway('students', $dbAdapter, null, $resultSetPrototype);
                 },

             ),
         );
     }
 }
