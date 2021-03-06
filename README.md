# Babl

[![Latest Stable Version](https://poser.pugx.org/mlo/babl/v/stable)](https://packagist.org/packages/mlo/babl)
[![License](https://poser.pugx.org/mlo/babl/license)](https://packagist.org/packages/mlo/babl)
[![Build Status](https://travis-ci.org/mloberg/Babl.svg?branch=master)](https://travis-ci.org/mloberg/Babl)
[![Coverage Status](https://coveralls.io/repos/mloberg/Babl/badge.svg?branch=master&service=github)](https://coveralls.io/github/mloberg/Babl?branch=master)

Toolkit for working with translation files in Symfony.

## Requirements

* PHP >= 5.4

## Installation

There are a couple ways you can install Babl.

### As a Phar (Recommended)

    $ curl http://mloberg.github.io/Babl/installer.php | php

This will place a `babl.phar` file in your current directory. From there you can
move it someplace easier to access (`/usr/local/bin`) and rename it to just
`babl`.

### Globally Through Composer

If you already have tools installed globally through composer, this is probably
the best way to go.

    $ composer global require mlo/babl --prefer-source

### As a Composer Dependency

You can also install Babl through composer so it's always available in your
project.

    $ composer require --dev mlo/babl

## Usage

To see the current version of Babl

    $ babl --version

To convert translation files between different formats, use the `convert`
command. For example if you have `messages.en.yml`, but want an XLIFF.

    $ babl convert app/Resources/translations/messages.en.yml xliff

The default format is `xliff`, but there is also `yml` and `php`.

If you want to add a translation entry to a file, use the `add` command.

    $ babl add app/Resources/translations/messages.en.yml greeting "Hello World!"

You can merge multiple translation files together. If the `--target` option is
not set, it will use the first file.

    $ babl merge --target translations.en.xliff messges.en.yml validator.en.xliff emails.en.php
