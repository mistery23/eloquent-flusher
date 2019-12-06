<?php
/**
 * PHP version 7.3
 *
 * @package Mistery23\EloquentSmartPushRelations
 * @author  Oleksandr Barabolia <alexandrbarabolya@gmail.com>
 */

namespace Mistery23\EloquentSmartPushRelations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

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
     *
     * @since v1.1.0 Add detach to HasMany relation type
     */
    public function push()
    {
        parent::push();

        foreach ($this->deleteRelations as $key => $items) {
            if ($this->$key() instanceof Relations\HasMany) {
                foreach ($items as $item){
                    if (!$item->delete()) {
                        return false;
                    }
                }
            } elseif ($this->$key() instanceof Relations\BelongsToMany) {
                $this->$key()->detach($items);
            } else {
                throw new \RuntimeException('Relation is not implements.');
            }

            unset($this->deleteRelations[$key]);
        }

        return true;
    }

    /**
     * @param string       $relationName
     * @param string|Model $item
     *
     * @return void
     */
    protected function detachItem(string $relationName, $item): void
    {
        $this->relationsExists($relationName);

        if (true === isset($this->deleteRelations[$relationName])) {
            $this->deleteRelations[$relationName] = array_merge($this->deleteRelations[$relationName], [$item]);

            return;
        }

        $this->deleteRelations[$relationName] = [$item];
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
