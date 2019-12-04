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

class User extends Model
{
    use SmartPushRelations;
    ....

    /**
     * Detach permission from a role.
     *
     * @param string $permissionId
     */
    public function detachPermission($permissionId)
    {
        $flag = $this->permissions->contains($permissionId);

        Assert::true($flag, 'Permission is not attached');

        $this->detachItem('permissions', $permissionId);
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