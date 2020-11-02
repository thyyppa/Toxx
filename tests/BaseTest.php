<?php namespace Tests;

use Hyyppa\Toxx\Contracts\JsonAndArrayOutput;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{

    /**
     * @param  string  $path
     *
     * @return string
     */
    protected function data(string $path) : string
    {
        return __DIR__.'/data/'.$path;
    }


    /**
     * @param  array  $expected
     * @param  array  $actual
     *
     * @return self
     */
    protected function assertArrayHas(array $expected, array $actual) : self
    {
        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $actual);

            if (is_array($value)) {
                $this->assertArrayHas($value, $actual[ $key ]);
                continue;
            }

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
    protected function assertArrayHasFuzzy(array $expected, array $actual) : self
    {
        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $actual);

            if (is_array($value)) {
                $this->assertArrayHasFuzzy($value, $actual[ $key ]);
                continue;
            }

            $this->assertStringContainsString((string) $value, (string) $actual[ $key ]);
        }

        return $this;
    }


    /**
     * @param          $expected
     * @param  string  $actual
     *
     * @return self
     */
    protected function assertJsonLike($expected, string $actual) : self
    {
        $this->assertArrayHas(
            is_string($expected) ? json_decode($expected, true) : $expected,
            json_decode($actual, true)
        );

        return $this;
    }


    /**
     * @param                      $expected
     * @param  JsonAndArrayOutput  $actual
     *
     * @return self
     */
    protected function assertJsonAndArrayLike($expected, JsonAndArrayOutput $actual) : self
    {
        $this->assertArrayHas($expected, $actual->array());
        $this->assertJsonLike($expected, $actual->json());

        return $this;
    }

}
