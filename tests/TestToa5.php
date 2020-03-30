<?php namespace Tests;

use Hyyppa\Toxx\Records\FieldFormat;
use Hyyppa\Toxx\Toxx;
use Hyyppa\Toxx\Utils\Settings;

class TestToa5 extends BaseTest
{


    public function testToa5() : void
    {
        $settings = Settings
            ::make()
            ->simplify()
//            ->removeSuffix()
            ->fieldFormat(FieldFormat::Pascal)
            ->transforms([
                'BatteryVoltage'    => function ($v) {
                    return $v * 100;
                },
                'BatteryVoltageMin' => function ($v) {
                    return (string) ($v / 100);
                },
                //                'Timestamp' => 'carbon|ymd',
                //                'Timestamp'         => function ($v) {
                //                    return Carbon::parse($v)->toDayDateTimeString();
                //                },
            ]);

        Toxx::defaultSettings(Settings::make([
            'transforms'   => [
                'Timestamp' => 'carbon|m/d/Y h:ia',
            ],
            'field_format' => FieldFormat::Kebab,
        ]));

        $dat = Toxx::load(__DIR__.'/data/DemoOutputToa5.dat', $settings);
        dump($dat->page(1, 3)->simple());

        $dat->settings()->fieldFormat(FieldFormat::Kebab);
        dump($dat->page(1, 3)->simple());

        $dat->settings()->fieldFormat(FieldFormat::Snake);
        dump($dat->page(1, 3)->simple());

        $dat->settings()->fieldFormat(FieldFormat::Lower);
        dump($dat->page(1, 3)->simple());


        $dat2 = Toxx::load(__DIR__.'/data/DemoOutputToa5.dat');
        dump($dat2->page(1, 3)->simple());
        $dat2->settings()->simplify();
        dump($dat2->page(1, 3)->simple());

        $sa = $dat2->settings()->toArray();
        $sj = $dat2->settings()->toJson();

        $dat3 = Toxx::load(__DIR__.'/data/DemoOutputToa5.dat');
        dump($dat3->page(1, 3)->simple());
        $dat3->settings()->fromJson($sj);
        dump($dat3->page(1, 3)->simple());

        $dat = Toxx::load(__DIR__.'/data/DemoOutputToaci1.dat', $settings);
        dump($dat->page(1, 3)->simple());

        $dat = Toxx::load(__DIR__.'/data/DemoOutputCSV.dat', [
            'panel_temp',
            'battery_voltage',
            'battery_voltage_min',
        ], $settings);
        dump($dat->page(1, 3)->simple());

        $dat = Toxx::load(__DIR__.'/data/DemoOutputAscii.dat', [
            'panel_temp',
            'battery_voltage',
            'battery_voltage_min',
        ], $settings);
        dump($dat->page(1, 3)->simple());

        $dat = Toxx::load(__DIR__.'/data/DemoOutputTob1.dat', $settings);
        dump($dat->page(1, 3)->simple());
        dump($dat->page(2, 3)->simple());
        dump($dat->last(3)->simple());
        dump($dat->info());


        $dat = Toxx::load(__DIR__.'/data/DemoOutputXML.xml', $settings);
        dump($dat->page(1, 3)->simple());
        dump($dat->page(2, 3)->simple());
        dump($dat->last(3)->simple());
        dump($dat->info());

//        dd();

        dd($dat->dateRange('2020-03-09 00:00:00', '2020-03-09 01:00:00')->human());
    }
}
