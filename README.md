## About
A wrapper to enumerate PHP 5.4+, 7+ timezones in a simplified way for use in various ways.

This is done with Laravel 5.x in mind, but may work elsewhere. YMMV elsewhere.   

### Basics   
- Creates timezone arrays based on PHP's supported timezones with optional grouping by region 
- Optionally group the arrays (multi-dim associated array) by region
- For either case, return those as:
    + php arrays for whatever use your heart desires
    + HTMLjessedp/timezones select list  

## Installation

You can install this package using [Composer](https://getcomposer.org).

- First, edit your project's `composer.json` file to require `jessedp/php-timezones`:

```php
...
"require": {
	...
    "jessedp/php-timezones": "0.1"
},
```

- Next, update Composer from the terminal (or your IDE if it does that):

```shell
$ composer update
```

- Finally, after the update completes, add the service provider. Open `config/app.php` and add a new item to the **providers**' array:

```php
...
'providers' => array(
    ...
    jessedp\Timezones\TimezonesServiceProvider::class,
),
```

## Usage

###### 1. Render a timezone listbox

The method `Timezones::create()` has three parameters:
```php
Timezones::create($name, $selected, $opts);
```
- $name **required** - the *name* of the select element
- $selected - sets the selected value of list box, assuming the a value with the option exists
- $opts an array of options as key=>value:
    + attr => array of key=>value pairs to be included in the select element (ie, 'id', 'class')   
    + with_regions => *boolean* whether or not to use option groups for the regions/continents (defaults to false)
    + regions => array (of strings) specifying the region(s) to include

Basic Example:
```php
Timezones::create('timezone');
```

Returns a string similar to:

    <select name="timezone">
        ...
            <option value="Africa/Abidjan">(GMT/UTC + 00:00) Abidjan</option>
            <option value="Africa/Accra">(GMT/UTC + 00:00) Accra</option>
        ...

    </select

     
Example:
```php
Timezones::create('timezone', 'Asia/Ho_Chi_Minh');
```

- The third parameter use to set HTML attribute of select tag.

Example:
```php
You may also add multiple attributes with an array.

Example:
```php
Timezones::create('timezone', null, ['attr'=>[
            'id'    => 'my_id',
            'class' => 'form-control'
            ]
            ]);
```

Which gives us:

    <select name="timezone" id="my_id" class="form-control">
    <option value="Pacific/Apia">(GMT/UTC + 14:00)&nbsp;&nbsp;&nbsp;&nbsp;Pacific/Apia</option><option value="Pacific/Kiritimati">(GMT/UTC + 14:00)&nbsp;&nbsp;&nbsp;&nbsp;Pacific/Kiritimati</option>
    ...
    <option value="Asia/Shanghai">(GMT/UTC + 08:00)&nbsp;&nbsp;&nbsp;&nbsp;Asia/Shanghai</option><option value="Asia/Singapore">(GMT/UTC + 08:00)&nbsp;&nbsp;&nbsp;&nbsp;Asia/Singapore</option>
    <option value="Asia/Taipei">(GMT/UTC + 08:00)&nbsp;&nbsp;&nbsp;&nbsp;Asia/Taipei</option>
    ...
    <option value="America/New_York">(GMT/UTC − 05:00)&nbsp;&nbsp;&nbsp;&nbsp;America/New York</option>
    ...
    </select>
   
Example:

Say you want the option groups but only a few regions... 

```php
Timezones::create('timezone',null,
                    ['attr'=>['class'=>'form-control'],
                    'with_regions'=>true,
                    'regions'=>['Africa','America']
                    ])
```

This will return a string similar to the following:

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

###### 2. Render a timezone array

You can also render timezone list as an array. To do so, just use the `Timezones::toArray()` method.

Example in Laravel:
```php
$timezone_list = Timezones::toArray();
```

# Thanks to...
This is based off some lovely work by:

- https://github.com/JackieDo/Timezone-List
- https://github.com/camroncade/timezone

