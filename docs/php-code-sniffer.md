### PHP Code Sniffer

- https://github.com/squizlabs/PHP_CodeSniffer

1. From CURL
```bash 
# Download using curl
curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar
curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar

# Or download using wget
wget https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar
wget https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar

# Then test the downloaded PHARs
php phpcs.phar -h
php phpcbf.phar -h

# Enable code-sniffer
./tools/phpcs -h
./tools/phpcbf -h
```


2. From Composer 
```bash 
composer global require "squizlabs/php_codesniffer=*"
```