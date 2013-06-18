<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = include __DIR__ . '/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

AnnotationRegistry::registerFile(
        __DIR__
                . '/../vendor/doctrine/mongodb-odm/lib/Doctrine/ODM/MongoDB/Mapping/Annotations/DoctrineAnnotations.php');

return $loader;
