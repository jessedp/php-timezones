<?php

namespace jessedp\Timezones;

use DateTime;
use DateTimeZone;

/**
 * The Timezones class.
 *
 * @package jessedp\Timezones
 * @author jessedp <jessedp@gmail.com>
 */
class Timezones
{
    /**
     * Whitespace seperate
     */
    const WHITESPACE_SEP = '&nbsp;&nbsp;&nbsp;&nbsp;';

    /**
     * Popular timezones
     *
     * @var array
     */
    protected $popularTimezones = [
        'GMT' => 'GMT timezone',
        'UTC' => 'UTC timezone',
    ];

    /**
     * The supported regions
     *
     * @var array
     */
    protected $regions = [
        'Africa'     => DateTimeZone::AFRICA,
        'America'    => DateTimeZone::AMERICA,
        'Antarctica' => DateTimeZone::ANTARCTICA,
        'Arctic'     => DateTimeZone::ARCTIC,
        'Asia'       => DateTimeZone::ASIA,
        'Atlantic'   => DateTimeZone::ATLANTIC,
        'Australia'  => DateTimeZone::AUSTRALIA,
        'Europe'     => DateTimeZone::EUROPE,
        'Indian'     => DateTimeZone::INDIAN,
        'Pacific'    => DateTimeZone::PACIFIC
    ];

    /**
     * Format to display timezones
     *
     * @param  string $timezone
     * @param  string $region
     *
     * @return array
     */
    protected function formatTimezone($timezone, $region)
    {
        $time = new DateTime(null, new DateTimeZone($timezone));
        $str_offset = $time->format('P');

        //clean up the html display
        $signs = ['-','+'];
        $signs_r = [' &minus; ',' &plus; '];
        $offset = str_replace( $signs, $signs_r, $str_offset);

        //do this for sorting later...
        $dbl_offset = (double)str_replace(':','.', $str_offset);

        //only strip things if we're bothering with regions
        if (!empty($region)) {
            $timezone = substr($timezone, strlen($region) + 1);
        }

        $timezone = str_replace('St_', 'St. ', $timezone);
        $timezone = str_replace('_', ' ', $timezone);

        $formatted = '(GMT/UTC' . $offset . ')' . self::WHITESPACE_SEP . $timezone;
        return ['offset'=>$dbl_offset, 'label' => $formatted];
    }

    /**
     * Create a timezone HTML select element for form
     *
     * @param string $name the name/id to be used for the element
     * @param string $selected selected option, defaults to UTC
     * @param array $opts various options to set, including:
     *      @subparam array $attr key=>value pairs of attributes to apply to the select element
     *      @subparam bool $with_regions whether or not to do region grouping (default=false)
     *      @subparam array $regions the regions to include, one or more of: Africa, America, Antarctica,
*                                    Arctic, Asia, Atlantic, Australia, Europe, Indian, Pacific
 * @return string
     **/
    public function create($name, $selected='UTC', $opts=[])
    {
        //handle a null selected
        if (empty($selected)) {
            $selected = 'UTC';
        }

        // Attributes for select element
        $attrSet = '';
        if (isset($opts['attr']) && is_array($opts['attr']) && !empty($opts['attr'])){
            $attr = $opts['attr'];

            foreach ($attr as $attr_name => $attr_value) {
                $attrSet .= ' ' .$attr_name. '="' .$attr_value. '"';
            }
        }

        //setup grouping
        $with_regions = false;
        if (isset($opts['with_regions']) && is_bool($opts['with_regions']) && $opts['with_regions']){
            $with_regions = true;
        }

        $limit_regions = [];
        //setup specfic regions - could be better and validate them here too, but, eh...
        if(isset($opts['regions']) && is_array($opts['regions']) && !empty($opts['regions'])){
            $limit_regions = $opts['regions'];
        }

        // start select element
        $listbox = '<select name="' .$name. '"' .$attrSet. '>';

        if ($with_regions) {
            $regions = $this->regions;
        } elseif(!empty($limit_regions)){
            foreach($limit_regions as $region){
                $regions[$region] = $this->regions[$region];
            }
        } else {
            $regions = ['All'=>DateTimeZone::ALL];
        }
        // Add all timezones of the regions
        // depending on with_regions, this may be one or muiltiple loops
        foreach ($regions as $continent => $mask) {
            $opts = [];
            $timezones = DateTimeZone::listIdentifiers($mask);

            if ($with_regions) {
                // start optgroup tag
                $listbox .= '<optgroup label="' . $continent . '">';
            } else {
                //when including everything, don't let formatTimeZone truncate the label.
                $continent = null;
            }

            // create option tags
            $offsets = [];
            $labels = [];
            foreach ($timezones as $timezone) {
                $opt = $this->formatTimezone($timezone, $continent);
                $opt['tz'] = $timezone;
                $opt['selected'] = ($selected == $timezone) ? ' selected="selected"' : '';
                $offsets[$timezone] = $opt['offset'];
                $labels[$timezone] = $opt['label'];
                $opts[] = $opt;
            }

            array_multisort($offsets, SORT_DESC,
                            $labels, SORT_ASC, $opts
                            );

            foreach($opts as $opt){
                $listbox .= '<option value="' . $opt['tz'] . '"' . $opt['selected'] . '>';
                $listbox .= $opt['label'];
                $listbox .= '</option>';
            }
            if ($with_regions) {
                // end optgroup tag
                $listbox .= '</optgroup>';
            }
        }

        // end select element
        $listbox .= '</select>';

        return $listbox;
    }

    /**
     * Create a timezone array
     *
     * @return mixed
     **/
    public function toArray()
    {
        $list = [];

        // Add popular timezones to list
        foreach ($this->popularTimezones as $key => $value) {
            $list['General'][$key] = $value;
        }

        // Add all timezone of the regions to return
        foreach ($this->regions as $continent => $mask) {
            $timezones = DateTimeZone::listIdentifiers($mask);
            foreach ($timezones as $timezone) {
                $list[$continent][$timezone] = $this->formatTimezone($timezone, $continent);
            }
        }

        return $list;
    }


    /**
     * @param integer $timestamp
     * @param string $timezone
     * @param string $format
     *
     * @return string
     */
    public static function convertFromUTC($timestamp, $timezone, $format = 'Y-m-d H:i:s')
    {
        $date = new DateTime($timestamp, new DateTimeZone('UTC'));

        $list = DateTimeZone::listIdentifiers();
        if (!in_array($timezone, $list)){
            $timezone = 'UTC';
        }
        $date->setTimezone(new DateTimeZone($timezone));

        return $date->format($format);
    }

    /**
     * Convert a timestamp to UTC
     * @param integer $timestamp
     * @param string $timezone
     * @param string $format
     *
     * @return string
     */
    public static function convertToUTC($timestamp, $timezone, $format = 'Y-m-d H:i:s')
    {
        $list = DateTimeZone::listIdentifiers();
        if (!in_array($timezone, $list)) {
            $timezone = 'UTC';
        }

        $date = new DateTime($timestamp, new DateTimeZone($timezone));

        $date->setTimezone(new DateTimeZone('UTC'));

        return $date->format($format);
    }
}
