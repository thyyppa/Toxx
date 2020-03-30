<?php namespace Hyyppa\Toxx\Traits;

use Hyyppa\Toxx\Contracts\SettingsInterface;
use Hyyppa\Toxx\Utils\Collection;
use Hyyppa\Toxx\Utils\Settings;

trait HasSettings
{

    /**
     * @var Settings
     */
    protected $_settings;


    /**
     * @param  SettingsInterface  $settings
     *
     * @return SettingsInterface|null
     */
    public function settings(SettingsInterface &$settings = null) : SettingsInterface
    {
        if ($settings === null) {
            return $this->_settings ?? $this->_settings = Settings::make();
        }

        $this->_settings = $settings;

        return $settings;
    }


    /**
     * @param  SettingsInterface  $settings
     *
     * @return SettingsInterface|null
     */
    public function itemSettings(SettingsInterface $settings = null) : ?SettingsInterface
    {
        if ($settings === null) {
            return $this->_settings;
        }

        $this->_settings = $settings;

        if ( ! $this instanceof Collection) {
            return $this;
        }

        $this->each(function ($item) use ($settings) {
            if ($this->objectHasSettings($item)) {
                $item->settings($settings);
            }

            return $item;
        });

        return $settings;
    }


    /**
     * @param  SettingsInterface  $settings
     * @param                     $field
     *
     * @return SettingsInterface|null
     */
    public function mapSettings(SettingsInterface $settings, $field) : ?SettingsInterface
    {
        $this->$field = $this->$field->map(
            static function ($item) use ($settings) {
                if ($this->objectHasSettings($item)) {
                    $item->settings($settings);
                }

                return $item;
            }
        );

        return $settings;
    }


    /**
     * @param $object
     *
     * @return bool
     */
    protected function objectHasSettings($object) : bool
    {
        if ( ! is_object($object)) {
            return false;
        }

        return array_search(
                   HasSettings::class,
                   class_uses($object) ?? [],
                   true
               ) !== false;
    }

}
