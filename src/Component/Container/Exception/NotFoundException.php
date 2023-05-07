<?php
namespace Laventure\Component\Container\Exception;

use Laventure\Component\Container\Contract\NotFoundExceptionInterface;

/**
 * @NotFoundException
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Container\Exception
*/
class NotFoundException extends \Exception implements NotFoundExceptionInterface
{

}