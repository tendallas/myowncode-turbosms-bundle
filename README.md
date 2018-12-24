Myowncode TurboSms Bundle
========================

This bundle helps you to integrate with http://turbosms.ua service in Symfony4 project.


Installation
------------

### Composer

You can use Composer for the automated process:

```bash
$ composer require myowncode/symfony-turbosms
```

or manually add link to bundle into your `composer.json` and run `$ composer update`:

```json
{
    "require" : {
        "myowncode/symfony-turbosms": "~1.0"
    }
}
```

Composer will install bundle to `vendor/myowncode/symfony-turbosms` directory.

### Adding bundle to your application kernel

```php
<?php

// app/config/bundles.php

return [
    // some other bundles
    Myowncode\TurboSmsBundle\MyowncodeTurbosmsBundle::class => ['all' => true],
];
```

### Configuration

```yaml
# app/config/packages/myowncode_turbosms.yaml

myowncode_turbosms:
    login: your_login
    password: your_password
    sender: your_sender_name
    # or false
    debug: true
    # or false
    save_to_db: true
    # default wsdl source (could change. Look at provider settings)
    wsdl: http://turbosms.in.ua/api/wsdl.html
```

Now you need create the tables in your database:

```bash
$ php bin/console doctrine:schema:update --dump-sql
```
or just perform migrations diff:
```bash
$ php bin/console doctrine:migrations:diff
```

This will show SQL queries for creating of tables in the database (or create new migrations). You may manually run these queries.

> **Note.**
You may also execute `php bin/console doctrine:schema:update --force` command, and Doctrine will create needed
tables for you. But I strongly recommend you to execute `--dump-sql` first and check SQL, which Doctrine will execute.


Use in controller
-----

```php
<?php 

namespace App\YourBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MyCustomController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction(): void
    {
        // some code goes here
        $this->get('myowncode_turbosms')->send("test", "+XXXXXXXXXXXX");
        // some code goes here
    }
}
```


TODO
-----
* Add tests
* Translate message
* Save log to file
* Integrate with EasyAdmin

## License

**symfony-turbosms** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.