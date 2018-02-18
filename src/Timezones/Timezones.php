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
        $int_offset = $time->format('P');
        $offset = str_replace('-', ' &minus; ', $int_offset);
        $offset = str_replace('+', ' &plus; ', $int_offset);

        $timezone = substr($timezone, strlen($region) + 1);
        $timezone = str_replace('St_', 'St. ', $timezone);
        $timezone = str_replace('_', ' ', $timezone);

        $formatted = '(GMT/UTC' . $offset . ')' . self::WHITESPACE_SEP . $timezone;
        return ['offset'=>$int_offset, 'label' => $formatted];
    }

    /**
     * Create a timezone HTML select element for form
     *
     * @param string $name the name/id to be used for the element
     * @param array $opts various options to set, including:
     *      @subparam string $selected the timezone name
     *      @subparam mixed $attr key=>value pairs of attributes to apply
     * @param string $selected
     * @param mixed $attr
     * @return string
     **/
    public function create($name, $selected='', $attr='')
    {
        // Attributes for select element
        $attrSet = null;
        if (!empty($attr)) {
            if (is_array($attr)) {
                foreach ($attr as $attr_name => $attr_value) {
                    $attrSet .= ' ' .$attr_name. '="' .$attr_value. '"';
                }
            } else {
                $attrSet = ' ' .$attr;
            }
        }

        // start select element
        $listbox = '<select name="' .$name. '"' .$attrSet. '>';

        // Add popular timezones
        $listbox .= '<optgroup label="General">';
        foreach ($this->popularTimezones as $key => $value) {
            $selected_attr = ($selected == $key) ? ' selected="selected"' : '';
            $listbox .= '<option value="' .$key. '" ' .$selected_attr. '>' .$value. '</option>';
        }
        $listbox .= '</optgroup>';

        // Add all timezone of the regions
        foreach ($this->regions as $continent => $mask) {
            $opts = [];
            $timezones = DateTimeZone::listIdentifiers($mask);

            // start optgroup tag
            $listbox .= '<optgroup label="' .$continent. '">';

            // create option tags
            foreach ($timezones as $timezone) {
                $opt = $this->formatTimezone($timezone, $continent);
                $opt['tz'] = $timezone;
                $opt['selected'] = ($selected == $timezone) ? ' selected="selected"' : '';
            }
            array_multisort(array_column($opts, 'offset'), SORT_ASC,
                            array_column($opts, 'label'), SORT_ASC,
                            $opts);
            foreach($opts as $opt){
                $listbox .= '<option value="' . $opt['timezone'] . '"' . $opt['selected'] . '>';
                $listbox .= $opt['label'];
                $listbox .= '</option>';
            }
            // end optgroup tag
            $listbox .= '</optgroup>';
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
        foreach ($this->continents as $continent => $mask) {
            $timezones = DateTimeZone::listIdentifiers($mask);
            foreach ($timezones as $timezone) {
                $list[$continent][$timezone] = $this->formatTimezone($timezone, $continent);
            }
        }

        return $list;
    }
}
