<?php

namespace App\Repository;

use BadMethodCallException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class MyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, $class)
    {
        parent::__construct($registry, $class);
    }

    public function __call(string $method, array $arguments): mixed
    {
        // Transform method name into "scope" + ucfirst(method)
        $scopeMethod = 'scope' . ucfirst($method);

        if (method_exists($this, $scopeMethod)) {
            return call_user_func_array([$this, $scopeMethod], $arguments);
        }

        throw new BadMethodCallException("Method {$method} does not exist.");
    }
}
