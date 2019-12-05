# Eloquent smart push relations
### Deffer push for unit tests

## Install
```
composer require mistery23/eloquent-smart-push-relations
```
Using
---
```
use Mistery23\EloquentSmartPushRelations\SmartPushRelations;

class Role extends Model
{
    use SmartPushRelations;
    ....

    /**
     * For BelongsToMany
     * Detach permission from a role.
     *
     * @param string $permissionId
     */
    public function detachPermission($permissionId): void
    {
        $flag = $this->permissions->contains($permissionId);

        Assert::true($flag, 'Permission is not attached');

        $this->detachItem('permissions', $permissionId);
    }

    .....

    /**
     * For HasMany
     * Detach role translations from a role.
     *
     * @param string $locale
     *
     * @return void
     */
    public function detachTranslation(string $locale): void
    {
        $translation = $this->translations->where('locale', $locale)->first();

        Assert::notNull($translation, 'Translation is not attached');

        $this->detachItem('translations', $translation);
    }
}
```

### And save in repository
---
```
    /**
     * Update role
     *
     * @param Role $role
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function update(Role $role): void
    {
        if (false === $role->push()) {
            throw new \RuntimeException('Update error.');
        }
    }
```