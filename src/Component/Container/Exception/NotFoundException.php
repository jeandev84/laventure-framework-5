<?php
namespace Laventure\Component\Container\Exception;

use Laventure\Component\Container\Contract\NotFoundExceptionInterface;

class NotFoundException extends \Exception implements NotFoundExceptionInterface
{

}