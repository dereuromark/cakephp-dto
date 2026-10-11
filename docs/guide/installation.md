# Installation

Run these commands from your CakePHP application's root:

```sh
composer require dereuromark/cakephp-dto
bin/cake plugin load CakeDto
```

Plugin loading registers the `dto init` and `dto generate` commands and the CakePHP collection adapter. If you load plugins in `Application::bootstrap()` instead, add `$this->addPlugin('CakeDto');` there once.

Composer checks PHP and CakePHP compatibility against the [package constraints](https://github.com/dereuromark/cakephp-dto/blob/master/composer.json). The default XML engine also needs PHP's DOM and libxml extensions. Check the CLI interpreter used by `bin/cake`:

```sh
php -m
bin/cake dto generate --help
```

No database tables or migrations are needed for DTO generation. The optional [schema importer](/reference/importing) has separate access configuration.

Keep this package as a runtime dependency when using its mapper, JSON response, validation bridge or collection integration. Generated DTOs also depend on `php-collective/dto`; generated PHP files are not independent of that library.

Continue with [Getting started](/guide/getting-started).
