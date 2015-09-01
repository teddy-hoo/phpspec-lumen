# phpspec Lumen Extension

This simply takes all the awesome work done by Ben Constable with his phpspec-laravel repo and makes it work with Laravel's new micro-framework, Lumen.

For most details you'll want to check out the original repo readme:  https://github.com/BenConstable/phpspec-laravel

## What's different

Aside from obvious things like the different package name, you'll want to update your phpspec.yml like so:

```yaml
extensions:
    - PhpSpec\Lumen\Extension\LumenExtension
```

And instead of extending your specs from `LaravelObjectBehavior` you'll do so instead from `LumenObjectBehavior`.
