<?php namespace Tests;

use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{

    /**
     * @param  string  $path
     *
     * @return string
     */
    public function data(string $path) : string
    {
        return __DIR__.'/data/'.$path;
    }


    /**
     * @param  array  $expected
     * @param  array  $actual
     *
     * @return self
     */
    public function assertArrayHas(array $expected, array $actual) : self
    {
        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $actual);
            $this->assertEquals($value, $actual[ $key ]);
        }

        return $this;
    }


    /**
     * @param  array  $expected
     * @param  array  $actual
     *
     * @return self
     */
    public function assertArrayHasFuzzy(array $expected, array $actual) : self
    {
        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $actual);
            $this->assertStringContainsString((string) $value, (string) $actual[ $key ]);
        }

        return $this;
    }

}
