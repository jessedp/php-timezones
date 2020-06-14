<?php 

namespace jessedp\Timezones\Tests;

use PHPUnit\Framework\TestCase;

use Timezones;

class TimezonesTest extends TestCase {

    /** @test */
    public function it_can_format_timezones(){
        $tzs = new Timezones;
        $tz = 'America/New-York';
        
        $this->assertSame('', $tzs->formatTimezone($tz));

    }


}
