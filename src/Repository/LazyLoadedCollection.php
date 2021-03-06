<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Repository;

use ArrayIterator;
use IteratorAggregate;
use IteratorIterator;
use Soliant\SimpleFM\Collection\CollectionInterface;
use Soliant\SimpleFM\Query\Conditions;
use Soliant\SimpleFM\Query\Field;
use Soliant\SimpleFM\Query\Query;
use Traversable;

final class LazyLoadedCollection implements IteratorAggregate, CollectionInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var string
     */
    private $idFieldName;

    /**
     * @var array
     */
    private $sparseRecords;

    /**
     * @var IteratorIterator
     */
    private $iterator;

    public function __construct(RepositoryInterface $repository, string $idFieldName, array $sparseRecords)
    {
        $this->repository = $repository;
        $this->idFieldName = $idFieldName;
        $this->sparseRecords = $sparseRecords;
    }

    public function getIterator() : Traversable
    {
        if (null !== $this->iterator) {
            return $this->iterator;
        }

        if (empty($this->sparseRecords)) {
            return $this->iterator = new ArrayIterator();
        }

        $query = new Query(...array_map(function (array $sparseRecord) : Conditions {
            return new Conditions(false, new Field($this->idFieldName, (string) $sparseRecord[$this->idFieldName]));
        }, $this->sparseRecords));

        return $this->iterator = new IteratorIterator($this->repository->findByQuery($query));
    }

    public function count() : int
    {
        return count($this->sparseRecords);
    }

    public function isEmpty() : bool
    {
        return 0 === count($this->sparseRecords);
    }

    public function first()
    {
        if ($this->isEmpty()) {
            return null;
        }

        $iterator = $this->getIterator();
        $iterator->rewind();
        return $iterator->current();
    }
}
