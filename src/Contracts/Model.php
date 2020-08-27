<?php

namespace SoluzioneSoftware\Laravel\Likable\Contracts;

interface Model
{
    /**
     * @return string
     */
    public function getTable();

    /**
     * @return string
     */
    public function getKeyName();

    /**
     * @return mixed
     */
    public function getKey();

    /**
     * @return string
     */
    public function getForeignKey();

    /**
     * @return string
     */
    public function getMorphClass();

    /**
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = []);

    /**
     * @return bool|null
     */
    public function delete();
}
