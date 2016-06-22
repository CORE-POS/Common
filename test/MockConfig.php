<?php

class MockConfig
{
    private $vals = array();
    public function get($k)
    {
        return isset($this->vals[$k]) ? $this->vals[$k] : '';
    }

    public function set($k, $v)
    {
        $this->vals[$k] = $v;
        return $this;
    }
}
