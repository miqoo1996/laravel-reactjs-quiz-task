<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractRepository
{
    abstract public function setModel(Model $model) : void;

    abstract protected function model() : Model;

    abstract protected function query() : Builder;
}
