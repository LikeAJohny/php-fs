# PHP FS (Filesystem Helpers)

Very basic tools to help you get around the filesystem with PHP.
 
This package is under active development.  
If you have any problems, suggestions or wishes, feel free to create an issue to let me know!

## Install

```shell
composer require likeajohny/php-fs
```


## Functions

- Directory
  - create
  - exists
  - remove
  - empty
  - move
  - copy
  - list
- File
  - create
  - exists
  - write
  - append
  - prepend
  - read
  - remove
  - copy
  - move


## File Usage Examples


### Create A New File

- The directory to create the file in has to exist.

```php
PhpFs\File::create('./filepath/cool-story-bro.txt');
```


### Write Content To A File

- Writes contents to a given file
- Overrides pre-existing content in the file

```php
PhpFs\File::write('./filepath/cool-story-bro.txt', 'Let me tell you a story!');
```


### Append To A File

- Appends content to a given file

```php
PhpFs\File::append('./filepath/cool-story-bro.txt', "\nAnd the story goes as follows:");
```


### Prepend To A File

- Prepends content to a given file

```php
PhpFs\File::prepend('./filepath/cool-story-bro.txt', "The stories foreword\n");
```


## Directory Usage Examples

### Remove A Directory

- always recursive

```php
PhpFs\Directory::remove('./path/to/directory');
```


### Empty A Directory

- always recursive

```php
PhpFs\Directory::empty('./path/to/directory');
```
