<?php

namespace Laventure\Component\Container;

use Closure;
use Exception;
use InvalidArgumentException;
use Laventure\Component\Container\Contract\ContainerAwareInterface;
use Laventure\Component\Container\Contract\ContainerInterface;
use Laventure\Component\Container\Exception\ContainerException;
use Laventure\Component\Container\Exception\NotFoundException;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;


/**
 * @Container
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Container
*/
class Container implements ContainerInterface, \ArrayAccess
{
    /**
     * store instance of container
     *
     * @var static
    */
    protected static $instance;



    /**
     * storage all bound params
     *
     * @var array
    */
    protected $bindings = [];




    /**
     * storage all instances
     *
     * @var array
    */
    protected $instances = [];




    /**
     * storage all instantiable abstracts
     *
     * @var array
    */
    protected $instantiables = [];




    /**
     * storage all resolved params
     *
     * @var array
    */
    protected $resolved  = [];




    /**
     * storage all shared
     *
     * @var array
    */
    protected $shared = [];




    /**
     * storage all aliases
     *
     * @var array
     */
    protected $aliases = [];



    /**
     * collection service providers
     *
     * @var array
    */
    protected $providers = [];





    /**
     * collection facades
     *
     * @var array
    */
    protected $facades = [];




    /**
     * @var array
    */
    protected $services = [];





    /**
     * Set the current container
     *
     * @param ContainerInterface|null $instance
     *
     * @return ContainerInterface|null
    */
    public static function setInstance(ContainerInterface $instance = null): ?ContainerInterface
    {
        return static::$instance = $instance;
    }




    /**
     * Return the instance of container
     *
     * @return static
    */
    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return self::$instance;
    }





    /**
     * Bind a simple abstract
     *
     * @param string $abstract
     *
     * @param $concrete
     *
     * @param bool $shared
     *
     * @return $this
    */
    public function bind(string $abstract, $concrete = null, bool $shared = false): static
    {
        $concrete = $this->resolveConcrete($concrete ?: $abstract);

        $this->bindings[$abstract] = $concrete;
        $this->shared[$abstract]   = $shared;

        return $this;
    }




    /**
     * Bind given bindings parameters
     *
     * @param array $bindings
     *
     * @return $this
    */
    public function binds(array $bindings): static
    {
        foreach ($bindings as $abstract => $concrete) {
            $this->bind($abstract, $concrete);
        }

        return $this;
    }




     /**
      * Determine if $abstract has been bounded
      *
      * @param string $abstract
      *
      * @return bool
     */
     public function bound(string $abstract): bool
     {
         return isset($this->bindings[$abstract]);
     }



     /**
      * Determine if given abstract has been shared.
      *
      * @param string $abstract
      *
      * @return bool
     */
     public function isShared(string $abstract): bool
     {
         return ! empty($this->shared[$abstract]) || $this->hasInstance($abstract);
     }




     /**
      * @param string $abstract
      * @param mixed $concrete
      * @return mixed
     */
     public function share(string $abstract, mixed $concrete): mixed
     {
         if (! $this->hasInstance($abstract)) {
              $this->instance($abstract, $concrete);
         }

         return $this->instances[$abstract];
     }




     /**
      * Returns bindings parameters
      *
      * @return array
     */
     public function getBindings(): array
     {
         return $this->bindings;
     }



    /**
     * Bind aliases given abstract
     *
     * @param string $abstract
     *
     * @param array $aliases
     *
     * @return $this
     */
    public function aliases(string $abstract, array $aliases): static
    {
        foreach ($aliases as $alias) {
            $this->alias($abstract, $alias);
        }

        return $this;
    }




    /**
     * Bind alias of given abstract
     *
     * @param string $alias
     *
     * @param string $abstract
     *
     * @return $this
    */
    public function alias(string $alias, string $abstract): static
    {
         $this->aliases[$alias] = $abstract;

         return $this;
    }




    /**
     * Determine if the given abstract has aliases
     *
     * @param string $abstract
     *
     * @return bool
     */
     public function hasAlias(string $abstract): bool
     {
         return isset($this->aliases[$abstract]);
     }




     /**
      * @param string $abstract
      * @return string
     */
     public function getAlias(string $abstract): string
     {
          if (! $this->hasAlias($abstract)) {
               return $abstract;
          }

          return $this->aliases[$abstract];
     }




     /**
      * Returns all aliases
      *
      * @return array
     */
     public function getAliases(): array
     {
          return $this->aliases;
     }




     /**
      * Returns resolved parameters
      *
      * @return array
     */
     public function getResolved(): array
     {
         return $this->resolved;
     }




     /**
      * Get concrete of given abstract
      *
      * @param string $abstract
      *
      * @return mixed
     */
     public function getConcrete(string $abstract): mixed
     {
         if (! $this->bound($abstract)) {
              return $abstract;
         }

         return $this->bindings[$abstract];
     }






    /**
     * Bind a singleton
     *
     * @param string $abstract
     *
     * @param $concrete
     *
     * @return $this
     */
    public function singleton(string $abstract, $concrete): static
    {
        return $this->bind($abstract, $concrete, true);
    }



    /**
     * @param array $bindings
     * @return $this
    */
    public function singletons(array $bindings): static
    {
        foreach ($bindings as $abstract => $concrete) {
            $this->singleton($abstract, $concrete);
        }

        return $this;
    }




    /**
     * Set instance of given abstract
     *
     * @param string $abstract
     *
     * @param mixed $concrete
     *
     * @return $this
    */
    public function instance(string $abstract, mixed $concrete): static
    {
        $this->instances[$abstract] = $this->resolveConcrete($concrete);

        return $this;
    }




    /**
     * Determine if the given abstract is instantiable
     *
     * @param string $abstract
     *
     * @return bool
    */
    public function classExists($abstract): bool
    {
         return is_string($abstract) && class_exists($abstract);
    }



    /**
     * @param string $abstract
     * @return bool
    */
    public function hasInstance(string $abstract): bool
    {
        return isset($this->instances[$abstract]);
    }




    /**
     * Set instances
     *
     * @param object[] $instances
     *
     * @return $this
    */
    public function instances(array $instances): static
    {
        foreach ($instances as $abstract => $instance) {
            $this->instance($abstract, $instance);
        }

        return $this;
    }


    /**
     * @param string $abstract
     * @param array $arguments
     * @return object
    */
    public function make(string $abstract, array $arguments = []): object
    {
        return $this->resolve($abstract, $arguments);
    }




    /**
     * Make a factory
     *
     * @param string $abstract
     *
     * @return object|null
    */
    public function factory(string $abstract): ?object
    {
        return $this->make($abstract);
    }





    /**
      * Determine if the given abstract has been resolved
      *
      * @param string $abstract
      *
      * @return bool
     */
     public function resolved(string $abstract): bool
     {
         return isset($this->resolved[$abstract]);
     }




    /**
     * Returns concrete value of bounded parameter
     *
     * @param string $abstract
     *
     * @param array $arguments
     *
     * @return mixed
     */
     public function resolve(string $abstract, array $arguments = []): mixed
     {
         $abstract = $this->getAlias($abstract);
         $concrete = $this->getConcrete($abstract);

         if ($this->isShared($abstract)) {
             return $this->share($abstract, $concrete);
         } elseif ($this->classExists($abstract)) {
             return $this->makeInstance($abstract, $arguments);
         }

         return $concrete;
     }




     /**
      * Make a new object and make new instance from given abstract
      *
      * @param string $abstract
      *
      * @param array $with
      *
      * @return object|null
     */
     private function makeInstance(string $abstract, array $with = []): ?object
     {
         return (function () use ($abstract, $with) {

              $reflection = new ReflectionClass($abstract);

              $constructor = $reflection->getConstructor();

              if (is_null($constructor)) {
                 return $reflection->newInstance();
              }

              $dependencies = $this->getDependencies($constructor->getParameters(), $with);

              return $reflection->newInstanceArgs($dependencies);

         })();

     }




     /**
      * @inheritDoc
     */
     public function get($id)
     {
         try {

             return $this->resolve($id);

         } catch (Exception $e) {

             throw new ContainerException("something went wrong for $id because {$e->getMessage()}", $e->getCode());
         }
     }





    /**
     * @inheritDoc
    */
    public function has($id): bool
    {
         return $this->bound($id) || $this->hasInstance($id) || $this->hasAlias($id);
    }



    /**
     * Call a closure with dependencies
     *
     * @param Closure $concrete
     *
     * @param array $with
     *
     * @return mixed
    */
    public function callAnonymous(Closure $concrete, array $with = []): mixed
    {
        return (function () use ($concrete, $with) {

            $object = new ReflectionFunction($concrete);

            $with = $this->getDependencies($object->getParameters(), $with);

            return $object->invoke(...$with);

        })();
    }




    /**
     * @param Closure|array $concrete
     *
     * @param array $arguments
     *
     * @return mixed
    */
    public function call(Closure|array $concrete, array $arguments = []): mixed
    {
          if ($concrete instanceof Closure) {
               return $this->callAnonymous($concrete, $arguments);
          }

          list($concrete, $method) = $concrete;

          if (! method_exists($concrete, $method)) {
               return false;
          }

         $reflection = new ReflectionMethod($concrete, $method);
         $arguments  = $this->getDependencies($reflection->getParameters(), $arguments);
         $object = $this->get($concrete);

         $implements = class_implements($object);

         if (isset($implements[ContainerAwareInterface::class])) {
            $object->setContainer($this);
         }

         return call_user_func_array([$object, $method], $arguments);
    }






    /**
     * Get dependencies
     *
     * @param ReflectionParameter[] $dependencies
     *
     * @param array $with
     *
     * @return array
     */
    private function getDependencies(array $dependencies, array $with): array
    {
        $resolved = [];

        foreach ($dependencies as $parameter) {
            [$name, $value] = $this->getDependency($parameter, $with);
            $this->resolved = array_merge($this->resolved, [$name => $value]);
            $resolved[]     = $value;
        }

        return $resolved;
    }




    /**
     * @param ReflectionParameter $parameter
     * @param array $with
     * @return array
     */
    private function getDependency(ReflectionParameter $parameter, array $with): array
    {
        $dependency    = $parameter->getClass();
        $parameterName = $parameter->getName();

        if (! $dependency) {
            return [$parameterName, $this->getValueOfParameter($parameter, $with)];
        }

        return [$dependency->getName(), $this->get($dependency->getName())];
    }




    /**
     * @param $concrete
     * @return mixed
    */
    private function resolveConcrete($concrete): mixed
    {
        if ($concrete instanceof Closure) {
            return $this->callAnonymous($concrete);
        } elseif ($this->classExists($concrete)) {
            return $this->make($concrete);
        }

        return $concrete;
    }


    /**
     * Returns value of given parameter
     *
     * @param ReflectionParameter $parameter
     *
     * @param array $with
     *
     * @return mixed
     * @throws NotFoundException
    */
    private function getValueOfParameter(ReflectionParameter $parameter, array $with): mixed
    {
        $name = $parameter->getName();

        if (array_key_exists($name, $with)) {
            return $with[$name];
        } elseif ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        $this->unresolvableDependencyException($parameter);
    }


    /**
     * @param ReflectionParameter $parameter
     *
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    private function unresolvableDependencyException(ReflectionParameter $parameter)
    {
         $message = "Unresolvable dependency [{$parameter}] in class {$parameter->getClass()->getName()}";

         throw new NotFoundException($message);
    }



    /**
     * @param $id
     * @return void
     */
    public function remove($id)
    {
        unset(
            $this->bindings[$id],
            $this->aliases[$id],
            $this->resolved[$id],
            $this->instances[$id]
        );
    }



    /**
     * @inheritDoc
    */
    public function offsetExists($offset): bool
    {
        return $this->bound($offset);
    }





    /**
     * @inheritDoc
    */
    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }





    /**
     * @inheritDoc
    */
    public function offsetSet($offset, $value): void
    {
         $this->bind($offset, $value);
    }





    /**
     * @inheritDoc
    */
    public function offsetUnset(mixed $offset): void
    {
        $this->remove($offset);
    }





    /**
     * @param $name
     * @return mixed
    */
    public function __get($name)
    {
        return $this[$name];
    }




    /**
     * @param $name
     * @param $value
    */
    public function __set($name, $value)
    {
        $this[$name] = $value;
    }




    /**
     * @return void
    */
    public function purge(): void
    {
        $this->bindings  = [];
        $this->instances = [];
        $this->aliases   = [];
        $this->resolved  = [];
    }

}
