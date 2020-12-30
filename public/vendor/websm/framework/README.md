**Installation**

run
```
composer init
```
in the root of your project

modify composer.json in the root directory of project

```
{
    "name": "your-user-name/your-project-name",
    "authors": [
        {
            "name": "your name",
            "email": "your email"
        }
    ],
    "repositories": [
        { "type": "composer", "url": "http://packagist.websm.io/" }
    ],
    "config": {
        "secure-http": false
    },
    "scripts": {
        "post-package-install": "zsh -c 'rm -rdf vendor/**/.git' || true",
        "post-package-update": "zsh -c 'rm -rdf vendor/**/.git' || true"
    }
}

```

for versioning specification refer to [composer documentation](https://getcomposer.org/doc/04-schema.md#version)

make sure to specify relevant version

then run

```
composer update
```

and

```
composer require websm/framework "~0.0.4"
```

if necessary
