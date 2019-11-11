<?php

namespace Kernel\Contracts;

abstract class View
{
    protected $data;

    /**
     * Small factory to simplify the use in controllers
     *
     * @param array $data
     * @return void
     */
    public static function make($data = [])
    {
        $new = new static();
        $new->setData($data);

        return $new;
    }

    /**
     * Set the data to be used in the template
     *
     * @param array $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the data to be used in the template
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get path to the tempalte file to be used during render
     * Force all views to declare their template file
     *
     * @return array
     */
    public abstract function getTemplatePath();
}