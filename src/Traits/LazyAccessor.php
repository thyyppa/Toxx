<?php namespace Hyyppa\Toxx\Traits;

/**
 * Bad practice, but feels so good.
 * You wanna fight about it??
 */
trait LazyAccessor
{


    /**
     * @param $param
     *
     * @return bool
     */
    protected function canBeLazy($param) : bool
    {
        switch (true) {
            case(method_exists($this, $param)):
            case(property_exists($this, $param)):
            case(property_exists($this, '_'.$param)):
                return true;
            default:
                return false;
        }
    }


    /**
     * @param $param
     *
     * @return mixed|null
     */
    public function shouldBeLazy($param)
    {
        switch (true) {
            case(method_exists($this, $param)):
                return $this->$param();
            case(property_exists($this, $param)):
                return $this->$param;
            case(property_exists($this, '_'.$param)):
                return $this->{'_'.$param};
            default:
                return null;
        }
    }


    /**
     * @param $param
     *
     * @return mixed|null
     */
    public function __get($param)
    {
        if ($this->canBeLazy($param)) {
            return $this->shouldBeLazy($param);
        }

        return null;
    }

}
