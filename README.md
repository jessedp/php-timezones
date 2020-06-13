# php-timezones

[![Build Status](https://api.travis-ci.org/jessedp/php-timezones.svg?branch=master)](https://travis-ci.org/jessedp/php-timezones)

A wrapper to enumerate PHP 5.6+, 7.x timezones in a simplified way for use in various ways.

This is done with Laravel 5.5+ in mind - YMMV elsewhere.

## Basics

- Creates timezone arrays based on PHP's supported timezones with optional grouping by region
- Lists are sorted by offset from high (+14:00) to low (-11:00)  
- Optionally group the arrays (multi-dim associated array) by region
  - sorting is the same, but only inside each region
- For either case, return those as:
  - php arrays for whatever use your heart desires
  - HTML select list  
- 2 utility functions for converting to/from UTC

## Installation

You can install this package using [Composer](https://getcomposer.org).

```bash
$ composer require jessedp/php-timezones

Using version ^0.2.0 for jessedp/php-timezones
./composer.json has been updated
    ...

```

## Usage

### 1. Render a timezone HTML Select list

The method `Timezones::create()` has three parameters:

```php
Timezones::create($name, $selected, $opts);
```

- $name **required** - the *name* of the select element
- $selected - sets the selected value of list box, assuming the a value with the option exists
- $opts an array of options as key=>value:
  - attr => *array* of key=>value pairs to be included in the select element (ie, 'id', 'class', etc.)
  - with_regions => *boolean* whether or not to use option groups for the regions/continents (defaults to false)
  - regions => array (of strings) specifying the region(s) to include

#### Basic Example

```php
Timezones::create('timezone');
```

Returns a string similar to:

```html
    <select name="timezone">
        ...
            <option value="Africa/Abidjan">(GMT/UTC + 00:00) Abidjan</option>
            <option value="Africa/Accra">(GMT/UTC + 00:00) Accra</option>
        ...

    </select>
```

#### "Selected" Example

Same as above, but *Asia/Ho_Chi_Minh* will be selected by default

```php
Timezones::create('timezone', 'Asia/Ho_Chi_Minh');
```

#### "Options" Example

You may also add multiple attributes with an array.

```php
Timezones::create('timezone', null,
            ['attr'=>[
             'id'    => 'my_id',
             'class' => 'form-control'
            ]
        ]);
```

Which gives us:

```html
    <select name="timezone" id="my_id" class="form-control">
    <option value="Pacific/Apia">(GMT/UTC + 14:00)&nbsp;&nbsp;&nbsp;&nbsp;Pacific/Apia</option><option value="Pacific/Kiritimati">(GMT/UTC + 14:00)&nbsp;&nbsp;&nbsp;&nbsp;Pacific/Kiritimati</option>
    ...
    <option value="Asia/Shanghai">(GMT/UTC + 08:00)&nbsp;&nbsp;&nbsp;&nbsp;Asia/Shanghai</option><option value="Asia/Singapore">(GMT/UTC + 08:00)&nbsp;&nbsp;&nbsp;&nbsp;Asia/Singapore</option>
    <option value="Asia/Taipei">(GMT/UTC + 08:00)&nbsp;&nbsp;&nbsp;&nbsp;Asia/Taipei</option>
    ...
    <option value="America/New_York">(GMT/UTC − 05:00)&nbsp;&nbsp;&nbsp;&nbsp;America/New York</option>
    ...
    </select>
```

#### "Regions/Grouping" Example

Say you want the option groups but only a couple regions...

```php
Timezones::create('timezone',null,
                    ['attr'=>['class'=>'form-control'],
                    'with_regions'=>true,
                    'regions'=>['Africa','America']
                    ])
```

This will return a string similar to the following:

```html
    <select name="timezone" class="form-control">
        <optgroup label="Africa">
            <option value="Africa/Addis_Ababa">(GMT/UTC + 03:00)&nbsp;&nbsp;&nbsp;&nbsp;Addis Ababa</option>
            <option value="Africa/Asmara">(GMT/UTC + 03:00)&nbsp;&nbsp;&nbsp;&nbsp;Asmara</option>
            ...
        </optgroup>
        <optgroup label="America">
            ...
            <option value="America/Noronha">(GMT/UTC − 02:00)&nbsp;&nbsp;&nbsp;&nbsp;Noronha</option>
            ...
            <option value="America/Argentina/Buenos_Aires">(GMT/UTC − 03:00)&nbsp;&nbsp;&nbsp;&nbsp;Argentina/Buenos Aires</option>
            ...
            <option value="America/New_York">(GMT/UTC − 05:00)&nbsp;&nbsp;&nbsp;&nbsp;New York</option>
            ...
        </optgroup>
    </select>
```

### 2. Render a timezone array

You can also render timezone list as an array. To do so, just use the `Timezones::toArray()` method.

Example in Laravel:

```php
$timezone_list = Timezones::toArray();
```

### 3. Utility methods

The package includes two methods that make it easy to deal with displaying and storing timezones, `convertFromUTC()` and `convertToUTC()`:

Each function accepts two required parameters and a third optional parameter dealing with the format of the returned timestamp.

```php
    Timezones::convertFromUTC($timestamp, $timezone, $format);
    Timezones::convertToUTC($timestamp, $timezone, $format);
```

The first parameter accepts a timestamp, the second accepts the name of the timezone that you are converting to/from. The option values associated with the timezones included in the select form builder can be plugged into here as is. Alternatively, you can use any of [PHP's supported timezones](http://php.net/manual/en/timezones.php).

The third parameter is optional, and default is set to `'Y-m-d H:i:s'`, which is how Laravel natively stores datetimes into the database (the `created_at` and `updated_at` columns). If you're using this for display purposes, you may find including `'(e)'` in the format string which displays the timezone.

## Thanks to

This is based off some lovely work by:

- https://github.com/JackieDo/Timezone-List
- https://github.com/camroncade/timezone
