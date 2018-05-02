# Robots.txt PSR-15 middleware 

[`RobotsTxtMiddleware`](src/RobotsTxtMiddleware.php) is a [PSR-15](https://www.php-fig.org/psr/psr-15/) middleware dedicated to answer `/robots.txt` requests. It uses [arcanedev/robots](https://github.com/ARCANEDEV/Robots) to generate the response in the [`robots.txt` format](https://developers.google.com/search/reference/robots_txt).


## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/robots-txt-middleware) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/robots-txt-middleware
```

## License

The library is published under the MIT license (see [`LICENSE`](LICENSE) file).