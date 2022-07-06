# MoosePress Error Management Class v. 1.0

This is a standalone version of the error class used by MoosePress, for those who want a really simple in-application error logger for their PHP apps.

## System Requirements

Requirements are pretty simple.
- Developed with PHP 8. Should run with PHP 7.
- A web server, or what's the point really?

## Dependencies

None.

## Defaults

The default namespace for this class is `mootly::mp_errors`.

The default location should be your vendor library. For MoosePress that would be `/_lib/mootly/mp_errors/`.

## Contents

The files in this set are as follows:

| path              | description
| ----------        | ----------
| mpc_errors.php    | The class definition.
| mpi_errors.php    | The interface for the class.
| mpt_errors.php    | Local unit test file to make sire things work.
