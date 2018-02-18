<?php namespace

jessedp\Timezones\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Timezones facade.
 *
 * @package jessedp\Timezones\Facades
 * @author jessedp <jessedp@gmail.com>
 */
class Timezones extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'timezones';
    }
}
