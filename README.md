# Common-Bundle
[![travis-ci](https://travis-ci.org/CORE-POS/Common-Bundle.svg?branch=master)](https://travis-ci.org/CORE-POS/Common-Bundle)
[![Test Coverage](https://codeclimate.com/github/CORE-POS/Common-Bundle/badges/coverage.svg)](https://codeclimate.com/github/CORE-POS/Common-Bundle/coverage)
Sub-project containing code common to CORE-POS Lane and Office. 

Currently includes:
* A SQL abstraction class supporting all MySQL subsystems
  and to a lesser extent MSSQL, Postgres, and SQLite
* A barebones ORM and SQL migration class (up only)
* Plugin base class for managing settings and class'
  plugin membership
* PSR-3 Logging
* File-based PSR Caching
* Idiosyncratic page building base class

See https://github.com/CORE-POS/IS4C for the main project
