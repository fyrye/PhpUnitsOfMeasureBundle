<?php
/**
 * UnitNamingTrait.php
 * @author fyrye <admin@fyrye.com>
 * @version 2018.11.05
 */

namespace Fyrye\Bundle\PhpUnitsOfMeasureBundle\DependencyInjection;

/**
 * Trait UnitNamingTrait
 * @package Fyrye\Bundle\PhpUnitsOfMeasureBundle\DependencyInjection
 */
trait UnitNamingTrait
{
    /**
     * @param string $name
     * @return string
     */
    private function normalizeName($name)
    {
        return preg_replace('/[^a-z0-9_]/', '', strtolower($name));
    }

    /**
     * @param string|object $class
     * @return string
     * @throws \ReflectionException
     */
    private function extractName($class)
    {
        return (new \ReflectionClass($class))->getShortName();
    }
}
