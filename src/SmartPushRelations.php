<?php
/**
 * PHP version 7.3
 *
 * @package Mistery23\EloquentSmartPushRelations
 * @author  Oleksandr Barabolia <alexandrbarabolya@gmail.com>
 */

namespace Mistery23\EloquentSmartPushRelations;

/**
 * Trait SmartPushRelations
 */
trait SmartPushRelations
{

    /**
     * @var array
     */
    private $deleteRelations = [];


    /**
     * Reload Eloquent method push.
     */
    public function push()
    {
        parent::push();

        foreach ($this->deleteRelations as $key => $items) {
            $this->$key()->detach($items);
            unset($this->deleteRelations[$key]);
        }
    }

    /**
     * @param string $relationName
     * @param string $itemId
     *
     * @return void
     */
    protected function detachItem(string $relationName, string $itemId): void
    {
        if (true === isset($this->deleteRelations[$relationName])) {
            $this->deleteRelations[$relationName] = array_merge($this->deleteRelations[$relationName], [$itemId]);
            return;
        }

        $this->deleteRelations[$relationName] = [$itemId];
    }

    /**
     * @param string $relationName
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    private function relationsExists(string $relationName): void
    {
        try {
            $this->getRelation($relationName);
        } catch (\Exception $e) {
            throw new \RuntimeException('Relation isn\'t present in model ' . get_class($this) . '.');
        }
    }
}
