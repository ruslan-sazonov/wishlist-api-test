<?php

namespace App\Generic\Hydrator;

use App\Generic\Interfaces\Arrayable;
use App\Generic\Interfaces\Hydrator\ResultCollectionInterface;
use App\Generic\Serializer\ArraySerializer;
use Doctrine\Common\Collections\ArrayCollection;

final class ResultCollection implements ResultCollectionInterface, Arrayable
{
    use ArraySerializer;

    /** @var array $items */
    private $items = [];

    /**
     * ResultCollection constructor.
     * @param array $items
     */
    public function __construct(array $items)
    {
        foreach ($items as $item) {
            $this->items[] = $this->toArray($item);
        }
    }

    /**
     * @param string $className
     * @return ArrayCollection
     */
    public function hydrateResultsAs(string $className)
    {
        $hydratedItems = new ArrayCollection();

        foreach ($this->items as $item) {
            $hydratedItems->add($className::fromArray($item));
        }

        return $hydratedItems;
    }

    /**
     * @param string $className
     * @return mixed
     */
    public function hydrateSingleResultAs(string $className)
    {
        $item = $this->getSingleResult();

        return $className::fromArray($item);
    }

    /**
     * @return mixed
     */
    public function getSingleResult()
    {
        return reset($this->items);
    }
}