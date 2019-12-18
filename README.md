# Eloquent flusher
### Deffer push entity

## Install
```
composer require mistery23/eloquent-flusher
```
Using
---
```
use Mistery23\Flusher;

class Role extends Model
{
    use Flusher;
    ....

    /**
     * For BelongsToMany.
     * Detach permission from a role.
     *
     * @param string $permissionId
     */
    public function detachPermission($permissionId): void
    {
        $this->detachItem('permissions', $permissionId);
    }

    .....

    /**
     * For HasMany.
     * Detach role translations from a role.
     *
     * @param string $locale
     *
     * @return void
     */
    public function detachTranslation(string $locale): void
    {
        $translation = $this->translations->where('locale', $locale)->first();

        $this->detachItem('translations', $translation);
    }
}
```

### And flush in repository
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
        if (false === $role->flush()) {
            throw new \RuntimeException('Update error.');
        }
    }
```
---
License
---
This package is free software distributed under the terms of the [MIT license](https://opensource.org/licenses/MIT). Enjoy!
