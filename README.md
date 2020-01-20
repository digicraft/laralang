<h1 align="center">Laravel Laralang</h1>

<p align="center">
Laralang is a language files manager in your artisan console based on Laravel Langman, it helps you search, update, add, and remove
translation lines with ease. Taking care of a multilingual interface is not a headache anymore.
<br>
<br>

<img src="http://s16.postimg.org/mghfe2v3p/ezgif_com_optimize.gif" alt="Laravel Laralang">
<br>
<a href="https://travis-ci.org/themsaid/laravel-laralang"><img src="https://travis-ci.org/themsaid/laravel-laralang.svg?branch=master" alt="Build Status"></a>
<a href="https://styleci.io/repos/55088784"><img src="https://styleci.io/repos/55088784/shield?style=flat" alt="StyleCI"></a>
<a href="https://packagist.org/packages/themsaid/laravel-laralang"><img src="https://poser.pugx.org/themsaid/laravel-laralang/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/themsaid/laravel-laralang"><img src="https://poser.pugx.org/themsaid/laravel-laralang/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/themsaid/laravel-laralang"><img src="https://poser.pugx.org/themsaid/laravel-laralang/license.svg" alt="License"></a>

</p>

## Installation

Begin by installing the package through Composer. Run the following command in your terminal:

```
$ composer require digicraft/laralang
```

Once done, add the following line in your providers array of `config/app.php`:

```php
Digicraft\Laralang\LaralangServiceProvider::class
```

This package has a single configuration option that points to the `resources/lang` directory, if only you need to change
the path then publish the config file:

```
php artisan vendor:publish --provider="Digicraft\Laralang\LaralangServiceProvider"
```

## Usage

### Showing lines of a translation file

```
php artisan laralang:show users
```

You get:

```
+---------+---------------+-------------+
| key     | en            | nl          |
+---------+---------------+-------------+
| name    | name          | naam        |
| job     | job           | baan        |
+---------+---------------+-------------+
```

---

```
php artisan laralang:show users.name
```

Brings only the translation of the `name` key in all languages.

---

```
php artisan laralang:show users.name.first
```

Brings the translation of a nested key.

---

```
php artisan laralang:show package::users.name
```

Brings the translation of a vendor package language file.

---

```
php artisan laralang:show users --lang=en,it
```

Brings the translation of only the "en" and "it" languages.

---

```
php artisan laralang:show users.nam -c
```

Brings only the translation lines with keys matching the given key via close match, so searching for `nam` brings values for
keys like (`name`, `username`, `branch_name_required`, etc...).

In the table returned by this command, if a translation is missing it'll be marked in red.

### Finding a translation line

```
php artisan laralang:find 'log in first'
```

You get a table of language lines where any of the values matches the given phrase by close match.

### Searching view files for missing translations

```
php artisan laralang:sync
```

This command will look into all files in `resources/views` and `app` and find all translation keys that are not covered in your translation files, after
that it appends those keys to the files with a value equal to an empty string.

### Filling missing translations

```
php artisan laralang:missing
```

It'll collect all the keys that are missing in any of the languages or has values equals to an empty string, prompt
asking you to give a translation for each, and finally save the given values to the files.

### Translating a key

```
php artisan laralang:trans users.name
php artisan laralang:trans users.name.first
php artisan laralang:trans users.name --lang=en
php artisan laralang:trans package::users.name
```

Using this command you may set a language key (plain or nested) for a given group, you may also specify which language you wish to set leaving the other languages as is.

This command will add a new key if not existing, and updates the key if it is already there.

### Removing a key

```
php artisan laralang:remove users.name
php artisan laralang:remove package::users.name
```

It'll remove that key from all language files.

### Renaming a key

```
php artisan laralang:rename users.name full_name
```

This will rename `users.name` to be `users.full_name`, the console will output a list of files where the key used to exist.

## Notes

`laralang:sync`, `laralang:missing`, `laralang:trans`, and `laralang:remove` will update your language files by writing them completely, meaning that any comments or special styling will be removed, so I recommend you backup your files.

## Web interface

If you want a web interface to manage your language files instead, I recommend [Laravel 5 Translation Manager](https://github.com/barryvdh/laravel-translation-manager)
by [Barry vd. Heuvel](https://github.com/barryvdh).
