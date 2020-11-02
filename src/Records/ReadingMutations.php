<?php namespace Hyyppa\Toxx\Records;

use Hyyppa\Toxx\Contracts\SettingsInterface;
use Hyyppa\Toxx\Utils\Format;

trait ReadingMutations
{

    /**
     * @return SettingsInterface|null
     */
    protected function settings() : ?SettingsInterface
    {
        if ( ! $this->_header->settings()) {
            return null;
        }

        return $this->_header->settings();
    }


    /**
     * @param $value
     *
     * @return bool|string
     */
    protected function readingMutation($value)
    {
        $value = $this->mutateType($value);

        if ( ! $this->settings()) {
            return $value;
        }

        $value = $this->mutatePrecision($value);
        $value = $this->mutateTransforms($value);

        return $value;
    }


    /**
     * @param $field
     *
     * @return string
     */
    protected function fieldMutation($field) : string
    {
        if ( ! $this->settings()) {
            return $field;
        }

        $field = $this->handleSuffix($field);
        $field = $this->mutateField($field);
        $field = $this->aliasField($field);

        return $field;
    }


    protected function unitMutation($value) : ?string
    {
        switch ($this->originalUnit()) {
            case('TS'):
            case('RN'):
            case('NANOSECONDS'):
            case('SECONDS'):
                return '';
            default:
                return $value;
        }
    }


    /**
     * @param $value
     *
     * @return mixed
     */
    protected function mutateType($value)
    {
        if ($this->type() === 'BOOL') {
            return $value === -1 || $value === true;
        }

        if ($this->originalField() === 'SECONDS') {
            return $value;
        }

        if (is_numeric($value)) {
            return $this->mutatePrecision($value);
        }

        return $value;
    }


    /**
     * @param $value
     *
     * @return float|mixed
     */
    protected function mutatePrecision($value)
    {
        if ( ! is_numeric($value)) {
            return $value;
        }

        $precision = $this->settings()->precision();

        if (is_array($precision)) {
            $precision = $this->normalizedGetForField('precision');
        }

        if ($precision === null) {
            return $value + 0;
        }

        if ($precision === 0) {
            return (int) round($value);
        }

        return (float) sprintf('%0.'.$precision.'f', $value);
    }


    /**
     * @param $value
     *
     * @return mixed
     */
    protected function mutateTransforms($value)
    {
        $transform = $this->normalizedGetForField('transforms');

        if (is_callable($transform)) {
            return $transform($value);
        }

        return $transform ? Format::text($value, $transform) : $value;
    }


    /**
     * @param $field
     *
     * @return string
     */
    protected function mutateField($field) : string
    {
        $format = $this->normalizedGet($field, 'fieldFormat');

        return $format ? Format::text($field, $format) : $field;
    }


    /**
     * @param  string  $field
     *
     * @return string
     */
    protected function handleSuffix(string $field) : string
    {
        if ( ! $this->settings()->_remove_suffix) {
            return $field;
        }

        if ( ! $suffix = $this->processing()) {
            return $field;
        }

        return str_ireplace('_'.$suffix, '', $field);
    }


    /**
     * @param $field
     *
     * @return string
     */
    protected function aliasField($field) : string
    {
        $aliases = $this->settings()->alias;
        if( ! array_key_exists($field, $aliases) ) {
            return $field;
        }

        return $aliases[$field];
    }


    /**
     * @return bool
     */
    public function isHidden() : bool
    {
        return $this->normalizedSearchForField('hidden') !== null;
    }


    /**
     * @param  string  $needle
     * @param          $haystack
     *
     * @return array|null
     */
    protected function normalizedSearch(string $needle, $haystack) : ?array
    {
        $needle = Format::snake($needle);

        if ( ! is_array($haystack)) {
            $haystack = [$haystack];
        }

        foreach ($haystack as $k => $v) {
            if (in_array($needle, [
                is_string($k) ? Format::snake($k) : $v,
                is_string($v) ? Format::snake($v) : $v,
            ], true)) {
                return [$k => $v];
            }
        }

        return null;
    }


    /**
     * @param $field
     * @param $option
     *
     * @return mixed|null
     */
    protected function normalizedGet($field, $option)
    {
        $field    = Format::snake($field);
        $haystack = $this->settings()->get($option);

        if ( ! is_array($haystack)) {
            return $haystack;
        }

        foreach ($haystack as $k => $v) {
            if (in_array($field, [
                is_string($k) ? Format::snake($k) : $v,
                is_string($v) ? Format::snake($v) : $v,
            ], true)) {
                return $v;
            }
        }

        return null;
    }


    /**
     * @param $option
     *
     * @return mixed|null
     */
    protected function normalizedGetForField($option)
    {
        return
            $this->normalizedGet($this->field(), $option) ??
            $this->normalizedGet($this->originalField(), $option);
    }


    /**
     * @param $option
     *
     * @return mixed|null
     */
    protected function normalizedSearchForField($option)
    {
        return
            $this->normalizedSearch(
                $this->field(),
                $this->settings()->get($option)
            ) ??
            $this->normalizedSearch(
                $this->originalField(),
                $this->settings()->get($option)
            );
    }

}
