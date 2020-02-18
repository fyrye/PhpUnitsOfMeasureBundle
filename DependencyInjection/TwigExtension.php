<?php
/**
 * TwigExtension.php
 * @author fyrye <admin@fyrye.com>
 * @version 2017.01.14
 */

namespace Fyrye\Bundle\PhpUnitsOfMeasureBundle\DependencyInjection;

use Fyrye\Bundle\PhpUnitsOfMeasureBundle\Registry\Manager;
use Fyrye\Bundle\PhpUnitsOfMeasureBundle\Registry\QuantityDefinition;
use PhpUnitsOfMeasure\PhysicalQuantityInterface;

class TwigExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @var array|\Twig\TwigFilter[]
     */
    private $filters = [];

    /**
     * @var Manager
     */
    private $manager;

    /**
     * TwigExtension constructor.
     * @param \Fyrye\Bundle\PhpUnitsOfMeasureBundle\Registry\Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->setManager($manager);
    }

    /**
     * @param array|QuantityDefinition[] $definitions
     */
    protected function setDefinitions($definitions)
    {
        foreach ($definitions as $name => $definition) {
            $this->filters[] = new \Twig\TwigFilter(
                'uom_' . $name,
                function ($value, $from, $to) use ($definition) {
                    return $definition->getUnit($value, $from)->toUnit($to);
                }
            );
        }
    }

    /**
     * @param Manager $manager
     * @return void
     */
    public function setManager(Manager $manager)
    {
        $this->manager = $manager;
        $this->setDefinitions($manager->getDefinitions());
    }

    /**
     * @param null|string $quantity
     * @param null|float $value
     * @param null|string $unit
     * @return Manager|PhysicalQuantityInterface
     */
    public function getManager($quantity = null, $value = null, $unit = null)
    {
        if (null === $quantity) {
            return $this->manager;
        }

        return $this->manager->getUnit($quantity, $value, $unit);
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('uom', [$this, 'getManager']),
        ];
    }

}
