<?php 

namespace jessedp\Timezones\Tests;

use PHPUnit\Framework\TestCase;

use jessedp\Timezones\Timezones;

class TimezonesTest extends TestCase {

    /** @test */
    public function it_can_format_timezones(){
        $tz = 'America/New_York';
        //actually check this...
        $want = [
            'offset' => -4.0,
            'label' => '(GMT/UTC &minus; 04:00)&nbsp;&nbsp;&nbsp;&nbsp;New York'
        ];

        $this->assertSame($want, Timezones::formatTimezone($tz, 'America'));

    }

    /** @test */
    public function basic_create_works(){
        $list = Timezones::create('test-list');

        $want = '<select name="test-list">';
        $this->assertStringStartsWith($want, $list);

    
        print("\nPHP VERSION:".phpversion());
        print("\nTZDB VERSION:".timezone_version_get()."\n");
        
        // these are from  6/13 , php 7.4 
        $want = 'Pacific/Pago Pago</option></select>';
        $this->assertStringEndsWith($want, $list);

        // $want = 44077;
        // $this->assertSame($want, strlen($list));

    }

    /** @test */
    public function create_with_select_works(){
        $list = Timezones::create('another-list', "America/New_York");

        $want = '<select name="another-list">';
        $this->assertStringStartsWith($want, $list);

        // these are from  6/13 , php 7.4
        $want = 'Pacific/Pago Pago</option></select>';
        $this->assertStringEndsWith($want, $list);
        $want = '<option value="America/New_York" selected="selected">';
        $this->assertTrue( strpos($list, $want) !== False , 'does not contain proper selected element' );
        $want = 1;
        $this->assertEquals( preg_match('/selected="selected"/', $list), $want);
    }

    /** @test */
    public function create_with_bad_select_works(){
        $list = Timezones::create('another-list', "America/Mayberry");

        $want = '<select name="another-list">';
        $this->assertStringStartsWith($want, $list);

        // these are from  6/13 , php 7.4
        $want = 'Pacific/Pago Pago</option></select>';
        $this->assertStringEndsWith($want, $list);
        $want = '<option value="America/Mayberry" selected="selected">';
        $this->assertTrue( strpos($list, $want) === False , 'does not contain proper selected element' );
        $want = 0;
        $this->assertEquals( preg_match('/selected="selected"/', $list), $want);

    }

    /** @test */
    public function create_with_attr_works(){
        $list = Timezones::create('another-list', "America/New_York", ['attr'=> ['attrOne'=>'val', 'attrTwo'=>'val2'] ] );

        $fields = 'attrOne="val" attrTwo="val"';
        $this->assertStringStartsWith('<select name="another-list" attrOne="val" attrTwo="val2">', $list);

    }

    /** @test */
    public function create_with_regions_works(){
        
        $list = Timezones::create('timezone',null,
                    ['attr'=>['class'=>'form-control'],
                    'with_regions'=>true,
                    'regions'=>['Africa','America']
                    ]);

        // $tz = new Timezone;
        // $list = $tz::create('another-list', "America/New_York", ['with_regions' => true]);
        
        //  print($list);
        foreach(Timezones::$regions as $region => $id){
            $want = '<optgroup label="'.$region.'">';
            $this->assertStringContainsString($want, $list);
        }
    }

    /** @test */
    public function create_limits_regions(){
        
        $list = Timezones::create('timezone',null,
                    ['attr'=>['class'=>'form-control'],
                    'with_regions'=>false,
                    'regions'=>['Africa','America']
                    ]);

        $want = "America/New_York";
        $this->assertStringContainsString($want, $list);

        $want = 'Pacific/Pago Pago';
        $this->assertFalse( strpos($want, $list) );

    }

    /** @test */
    public function lets_see_if_toArray_still_works(){
        
        $list = Timezones::toArray();
    
        $this->assertCount(11, $list);
        $this->assertArrayHasKey('Antarctica', $list);
        $this->assertArrayHasKey('General', $list);
        $this->assertArrayHasKey('America', $list);
        $this->assertArrayNotHasKey('Mordor', $list);
                
    }

}

