# MoosePlum Error Management Class

This is a standalone version of the error class used by MoosePlum, for those who want a really simple in-application error logger for their PHP apps.

This is for logging errors that are internal to the application and do not, and in most cases should not, generate system errors.

**This is NOT a secure solution.**

The error log is stored internally in an array that defaults to only storing the last 100 entries.

The class also stores an array of status messages for use in reporting. It starts with a default set that can be added to.

The primary function of this class is to standardize error logging across all objects in an application.

## System Requirements

Requirements are pretty simple.

- This was developed using PHP 8.1. It should work in PHP 7.0 and up, but has **not** been test for backward compatibility.
- A web server, or what's the point really?

## Dependencies

None.

## Defaults

The default namespace for this class is '`mpc`', which is short for 'MoosePlum Class'.

The default location for this class definition should be your vendor library. For inclusion with other MoosePlum stuff that would be `/_lib/mootly/mp_errors/`.

## Contents

The files in this set are as follows:

| path                | description
| ----------          | ----------
| composer.json       | Yep, we are using [Composer](https://getcomposer.org).
| LICENSE.md          | License notice [MIT](https://mit-license.org).
| README.md           | This document.
| mpt_errors.php      | Local unit test file to make sure things work.
| src/mpc_errors.php  | The class definition.
| src/mpi_errors.php  | The interface for the class.

## Installation

### Manual Installation

Put this class definition and any dependencies into your vendor library. For inclusion with other MoosePlum stuff that would be `/_lib/mootly/mp_errors/`.

Use your preferred method for including classes in your code.

### Composer Installation

This class definition is listed on [Packagist](https://packagist.org/users/Mootly/packages/) for installation using Composer.

See the [Composer](https://getcomposer.org) website for a directions on how to properly install Composer on your system.

Once Composer is installed and running, add the following code to the `composer.json` file at the root of your website.

Make sure you have the following listed as required. Adjust version numbers as necessary. See the `composer.json` in this class definition for required versions of dependencies for this version of the package.

```json
"require": {
  "php": ">=8.0.0",
  "mootly/mp_errors": "*",
}
```

If necessary for your configuration, make sure you have the following autoload definitions listed in your `composer.json`. Adjust the first step in the path as needed for the location of your vendor library.

```json
"autoload": {
  "classmap": [
    "_lib/mootly/mp_errors",
  ]
}
```

In your terminal app of choice, navigate to the root of your website and run the following command. (Depending on how you installed composer, this may be different.)

```pwsh
composer update
```

This should install this class definition and any related dependencies in your vendor library and sets up composer to link them into your application.

To be safe you can also run the following to rebuild the composer autoloader and make sure your classes are correctly registered.

```pwsh
composer dump-autoload -o
```

Make sure you have the following line to your page or application initialization code before using this class definition. Adjust accordingly based on the location of your vendor library.

```php
require_once "<site root>/<vendor lib>/autoload.php";
```

That should be all your need to do to get it up and running.

## Instantiation

If you are using autoloading, the recommended method for instantiation is as follows:

```php
if (!isset($mpo_errors)) { $mpo_errors  = new \mpc\mpc_errors(); }
```

The constructor takes three optional arguments.

1. Status Codes (array) - An array of status codes. See the format below.
2. Replace Flag (bool) - Whether to allow existing status codes to be replaced or not when the code is matched by a new entry. Default is false. True will override at any time.
3. Log Size (int) - Length of status log. Default is 100.

It is recommended that you create a single class instance and load it into your other objects as a depedency. For example:

```php
if (!isset($mpo_secure)) { $mpo_secure  = new \mpc\mpc_secure($mpo_errors); }
```

## Usage

The use of namespaces or other unique identifiers to create unique strings for locking is strongly encouraged.

Examples:

- mpo_parts::main_body
- mpo_menus::main_nav::home_link

Autogeneration examples:

- `get_class().'::'.someProp`
- `get_class().'::'.__METHOD__`
- `get_class().'_'.self::$iCount++` (for multiple instances)

For security add a hash of some sort that is always used for all calls by a given class. This prevents others without access to private properties from overwriting any locks. Examples of PHP hash generators:

- `php md5(get_class())`
- `md5(rand())`
- `uniqid()`
- `bin2hex(random_bytes(16))`

Since these will only persist for as long as it takes for PHP to generate and send out an HTTP response, they do not need to be overly secure. There are only milliseconds to guess the hash before it is gone.

MoosePlum classes define the following property on instantiation to ensure unique names.

```php
$this->classRef = bin2hex(random_bytes(8)).'::'.get_class();
```

### The Status Code Array

Status codes are stored in an array. Each array element is structured as follows:

```php
string status code => [ string severity, string message ]
```

For example, these are the predefined status codes.

```php
'none'          => ['Notice'  ,'Success.'],
'noAction'      => ['Notice'  ,'No action taken.'],
'mpe_set00'     => ['Warning' ,'Some properties already exist and were not replaced.'],
'mpe_set01'     => ['Warning' ,'This property already exists and was not replaced.'],
'mpe_locked'    => ['Warning' ,'Requested property is locked from updates.'],
'mpe_secure'    => ['Warning' ,'Requested property is secured from further updates.'],
'unknown'       => ['Error'   ,'Unknown error code.'],
'mpe_null'      => ['Error'   ,'Requested value or property does not exist.'],
'mpe_nomethod'  => ['Error'   ,'Requested method does not exist.'],
'mpe_badURL'    => ['Error'   ,'Invalid URL.'],
'mpe_param00'   => ['Error'   ,'Invalid parameter.'],
'mpe_param01'   => ['Error'   ,'Invalid history length.'],
'mpe_param02'   => ['Error'   ,'Attempted to add invalid error message entry.'],
'mpe_param03'   => ['Error'   ,'Too few parameters.'],
'mpe_param04a'  => ['Error'   ,'Too many parameters.'],
'mpe_param04b'  => ['Warning' ,'Too many parameters. Not all were processed.'],
```

### The Status Log

Status messages are added to an array that is defined as follows:

```php
log => [
  success  => bool
  code     => string  // status code from status code array
  source   => string  // calling class and method
  severity => string  // severity from status code array
  message  => string  // message from status code array
]
```

The array functions as a stack so last in, first out. The most recent status code is entered at position 0.

A `get()` call will return the last element recorded as an array with the above values, or false if an out of range request is made.

### Methods

#### setStatus

Add an entry to the status log. See above for the structure of the status log.

```php
public setStatus(string code, ?string source) : bool
```

If the status log exceeds maximum size, remove the oldest element.

If a source is not specified, log as 'source not specified'.

Return false is the status code does not exist in the status code array.

#### getStatus

Return the most recently logged status message as an array (see above), or the one specified in the call. The most recent message has a position of 0.

Return false if asking for an out of bounds element.

```php
public getStatus(int index) : array|bool
```

#### getStatusCodes

Return array of all available error codes.

This method tkes no arguments.

```php
public getStatusCodes() : array
```

#### getStatusCount

Return the number of messages in the status log.

This method takes no arguments.

```php
public getStatusCount() : int
```

#### addStatusCodes

Submit an array of error codes to add to the status code array.

Pass a replaces flag of true to overwrite existing codes in the case of matches.

See above for the structure of the status code array.

```php
public addStatusCodes(array codes, bool replace) : bool
```
