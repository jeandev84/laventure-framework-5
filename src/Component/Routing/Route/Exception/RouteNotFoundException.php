<?php
namespace Laventure\Component\Routing\Route\Exception;

class RouteNotFoundException extends \Exception
{
     /**
      * @param string $message
     */
     public function __construct(string $message)
     {
         parent::__construct($message, 404);
     }
}