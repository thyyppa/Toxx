<?php namespace Tests;

use Hyyppa\Toxx\Exceptions\SettingsException;
use Hyyppa\Toxx\Utils\Settings;

class SettingsTest extends BaseTest
{

    /**
     *
     */
    public function testSettings() : void
    {
        $settings = Settings::make();

        $this->assertEquals([], $settings->hidden);
        $this->assertEquals([], $settings->disabled_units);
        $this->assertEquals([], $settings->alias);
        $this->assertEquals([], $settings->transforms);
        $this->assertEquals([], $settings->field_format);
        $this->assertEquals([], $settings->precision);

        $this->assertEquals([], $settings->get('hidden'));
        $this->assertEquals([], $settings->get('disabled_units'));
        $this->assertEquals([], $settings->get('alias'));
        $this->assertEquals([], $settings->get('transforms'));
        $this->assertEquals([], $settings->get('field_format'));
        $this->assertEquals([], $settings->get('precision'));

        $this->assertEquals([], $settings->hidden);
        $this->assertEquals([], $settings->disabledUnits);
        $this->assertEquals([], $settings->alias);
        $this->assertEquals([], $settings->transforms);
        $this->assertEquals([], $settings->fieldFormat);
        $this->assertEquals([], $settings->precision);

        $this->assertEquals([], $settings->get('hidden'));
        $this->assertEquals([], $settings->get('disabledUnits'));
        $this->assertEquals([], $settings->get('alias'));
        $this->assertEquals([], $settings->get('transforms'));
        $this->assertEquals([], $settings->get('fieldFormat'));
        $this->assertEquals([], $settings->get('precision'));
    }


    /**
     *
     */
    public function testQuickSettings() : void
    {
        $settings = Settings::make()->simplify();

        $this->assertEquals([
            'SECONDS',
            'RECORD',
            'TABLE',
            'NANOSECONDS',
            'YEAR',
            'DAY',
            'TIME',
        ], $settings->hidden);

        $this->assertEquals([
            'RECORD',
            'SECONDS',
            'TIMESTAMP',
            'NANOSECONDS',
        ], $settings->disabled_units);

        $this->assertEquals([], $settings->alias);
        $this->assertEquals([], $settings->transforms);
        $this->assertEquals('snake', $settings->field_format);

        $this->assertEquals([
            'RECORD'  => 0,
            'SECONDS' => 0,
        ], $settings->precision);
    }


    /**
     *
     */
    public function testSettingsAssignment() : void
    {
        $settings = Settings
            ::make()
            ->hidden([
                'a' => 'aaa',
                'b' => 'bbb',
            ])
            ->disabledUnits([
                'c' => 'ccc',
                'd' => 'ddd',
            ])
            ->alias([
                'e' => 'eee',
                'f' => 'fff',
            ])
            ->transforms([
                'g' => 'ggg',
                'h' => 'hhh',
            ])
            ->fieldFormat([
                'i' => 'iii',
                'j' => 'jjj',
            ])
            ->precision([
                'k' => 'kkk',
                'l' => 'lll',
            ]);

        $this->assertEquals([
            'a' => 'aaa',
            'b' => 'bbb',
        ], $settings->hidden);

        $this->assertEquals([
            'c' => 'ccc',
            'd' => 'ddd',
        ], $settings->disabled_units);

        $this->assertEquals([
            'e' => 'eee',
            'f' => 'fff',
        ], $settings->alias);

        $this->assertEquals([
            'g' => 'ggg',
            'h' => 'hhh',
        ], $settings->transforms);

        $this->assertEquals([
            'i' => 'iii',
            'j' => 'jjj',
        ], $settings->field_format);

        $this->assertEquals([
            'k' => 'kkk',
            'l' => 'lll',
        ], $settings->precision);


        $settings = Settings::make()->fieldFormat('test');
        $this->assertEquals('test', $settings->field_format);
    }


    /**
     *
     */
    public function testSettingsAssignmentByProperty() : void
    {
        $settings = Settings::make();

        $settings->hidden = [
            'a' => 'aaa',
            'b' => 'bbb',
        ];

        $settings->disabled_units = [
            'c' => 'ccc',
            'd' => 'ddd',
        ];

        $settings->alias = [
            'e' => 'eee',
            'f' => 'fff',
        ];

        $settings->transforms = [
            'g' => 'ggg',
            'h' => 'hhh',
        ];

        $settings->field_format = [
            'i' => 'iii',
            'j' => 'jjj',
        ];

        $settings->precision = [
            'k' => 'kkk',
            'l' => 'lll',
        ];

        $this->assertEquals([
            'a' => 'aaa',
            'b' => 'bbb',
        ], $settings->hidden);

        $this->assertEquals([
            'c' => 'ccc',
            'd' => 'ddd',
        ], $settings->disabled_units);

        $this->assertEquals([
            'e' => 'eee',
            'f' => 'fff',
        ], $settings->alias);

        $this->assertEquals([
            'g' => 'ggg',
            'h' => 'hhh',
        ], $settings->transforms);

        $this->assertEquals([
            'i' => 'iii',
            'j' => 'jjj',
        ], $settings->field_format);

        $this->assertEquals([
            'k' => 'kkk',
            'l' => 'lll',
        ], $settings->precision);


        $settings->field_format = 'test';
        $this->assertEquals('test', $settings->field_format);
    }


    /**
     *
     */
    public function testMakeFromArray() : void
    {
        $settings_array = [
            'hidden'         => [
                'a' => 'aaa',
                'b' => 'bbb',
            ],
            'disabled_units' => [
                'c' => 'ccc',
                'd' => 'ddd',
            ],
            'alias'          => [
                'e' => 'eee',
                'f' => 'fff',
            ],
            'transforms'     => [
                'g' => 'ggg',
                'h' => 'hhh',
            ],
            'field_format'   => [
                'i' => 'iii',
                'j' => 'jjj',
            ],
            'precision'      => [
                'k' => 'kkk',
                'l' => 'lll',
            ],
        ];

        $settings = Settings::make($settings_array);

        $this->assertEquals([
            'a' => 'aaa',
            'b' => 'bbb',
        ], $settings->hidden);

        $this->assertEquals([
            'c' => 'ccc',
            'd' => 'ddd',
        ], $settings->disabled_units);

        $this->assertEquals([
            'e' => 'eee',
            'f' => 'fff',
        ], $settings->alias);

        $this->assertEquals([
            'g' => 'ggg',
            'h' => 'hhh',
        ], $settings->transforms);

        $this->assertEquals([
            'i' => 'iii',
            'j' => 'jjj',
        ], $settings->field_format);

        $this->assertEquals([
            'k' => 'kkk',
            'l' => 'lll',
        ], $settings->precision);
    }


    /**
     *
     */
    public function testMakeFromJson() : void
    {
        $settings = Settings::make(
            '{
              "hidden": {
                "a": "aaa",
                "b": "bbb"
              },
              "disabled_units": {
                "c": "ccc",
                "d": "ddd"
              },
              "alias": {
                "e": "eee",
                "f": "fff"
              },
              "transforms": {
                "g": "ggg",
                "h": "hhh"
              },
              "field_format": {
                "i": "iii",
                "j": "jjj"
              },
              "precision": {
                "k": "kkk",
                "l": "lll"
              }
            }'
        );

        $this->assertEquals([
            'a' => 'aaa',
            'b' => 'bbb',
        ], $settings->hidden);

        $this->assertEquals([
            'c' => 'ccc',
            'd' => 'ddd',
        ], $settings->disabled_units);

        $this->assertEquals([
            'e' => 'eee',
            'f' => 'fff',
        ], $settings->alias);

        $this->assertEquals([
            'g' => 'ggg',
            'h' => 'hhh',
        ], $settings->transforms);

        $this->assertEquals([
            'i' => 'iii',
            'j' => 'jjj',
        ], $settings->field_format);

        $this->assertEquals([
            'k' => 'kkk',
            'l' => 'lll',
        ], $settings->precision);
    }


    /**
     *
     */
    public function testMakeFromArrayError() : void
    {
        $this->expectException(SettingsException::class);
        Settings::make([
            'bad_settings' => [
                'a' => 'aaa',
                'b' => 'bbb',
            ],
        ]);
    }


    /**
     *
     */
    public function testMakeFromJsonError() : void
    {
        $this->expectException(SettingsException::class);
        Settings::make(
            '{
               "bad_settings": {
                 "a": "aaa",
                 "b": "bbb"
               }
             }'
        );
    }


}
