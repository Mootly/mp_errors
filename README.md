# MoosePlum Error Management Class

This is a standalone version of the error class used by MoosePlum, for those who want a really simple in-application error logger for their PHP apps.

## System Requirements

Requirements are pretty simple.
- This was developed using PHP 8.1.
- A web server, or what's the point really?

## Dependencies

None.

## Defaults

The default namespace for this class is `mpc`.

The default location should be your vendor library. For inclusion with other MoosePlum stuff that would be `/_lib/mootly/mp_errors/`.

## Contents

The files in this set are as follows:

| path              | description
| ----------        | ----------
| composer.json     | Yep, we are using [Composer](https://getcomposer.org).
| LICENSE.md        | License notice [MIT](https://mit-license.org).
| mpc_errors.php    | The class definition.
| mpi_errors.php    | The interface for the class.
| mpt_errors.php    | Local unit test file to make sure things work.
