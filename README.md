# PHP FS (Filesystem Helpers)

Very basic tools to help you get around the filesystem with PHP.

This package is far from feature complete and only provides the most basic stuff regarding directory & file operations.  
It is under active development, though. Definitely expect more possibilities in the near future.

## Install

```shell
composer require likeajohny/php-fs
```


## Features

- Directory
  - create
  - remove
  - empty
  - copy
  - move
- File
  - create
  - remove
  - copy
  - move


## Usage Examples

### Create a new directory

- 0777 & recursive by default.

```php
PhpFs\Directory::create('./path/to/directory');
```


### Create a new file

- The directory to create the file in has to exist.

```php
PhpFs\File::create('./filepath/cool-story-bro.txt');
```


### Remove a directory

- always recursive

```php
PhpFs\Directory::remove('./path/to/directory');
```


### Empty a directory

- always recursive

```php
PhpFs\Directory::empty('./path/to/directory');
```
