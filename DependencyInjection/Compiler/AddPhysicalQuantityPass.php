<?php
/**
 * AddPhysicalQuantityPass.php
 * @author fyrye <admin@fyrye.com>
 * @version 2017.01.15
 */

namespace Fyrye\Bundle\PhpUnitsOfMeasureBundle\DependencyInjection\Compiler;

use Fyrye\Bundle\PhpUnitsOfMeasureBundle\DependencyInjection\UnitNamingTrait;
use Fyrye\Bundle\PhpUnitsOfMeasureBundle\Registry\Manager;
use Fyrye\Bundle\PhpUnitsOfMeasureBundle\Registry\QuantityDefinition;
use PhpUnitsOfMeasure\AbstractPhysicalQuantity;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * {@inheritDoc}
 */
class AddPhysicalQuantityPass implements CompilerPassInterface
{
    use UnitNamingTrait;

    /**
     * You can modify the container here before it is dumped to PHP code.
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(Manager::class) ||
            !class_exists(AbstractPhysicalQuantity::class)
        ) {
            return;
        }
        if ($services = $container->findTaggedServiceIds('php_units_of_measure.extension', true)) {
            $managerDefinition = $container->getDefinition(Manager::class);
            foreach (\array_keys($services) as $id) {
                //retrieve the tagged service definition and remove it
                $serviceDefinition = $container->getDefinition($id);
                $container->removeDefinition($id);
                //create a QuantityDefinition to proxy the tagged service
                $class = $serviceDefinition->getClass();
                $name = $this->extractName($class);
                $container->register($class, QuantityDefinition::class)
                    ->setArguments([$name, $class])
                    ->setPublic(true);
                $container->setAlias('php_units_of_measure.quantity.' . $this->normalizeName($name), $class)
                    ->setPublic(true);
                $managerDefinition->addMethodCall('registerDefinition', [new Reference($class)]);
            }
        }
    }
}
