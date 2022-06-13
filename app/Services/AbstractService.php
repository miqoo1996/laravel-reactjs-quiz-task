<?php

namespace App\Services;

use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractService
{
    public function fetchById(AbstractRepository $repository, $id = null, bool $failOnFind = false, bool $reload = false) :? Model
    {
        $repository->applyId($id);

        $model = $repository->first($failOnFind, $reload);

        if ($reload) {
            $repository->setModel($model);
        }

        return $model;
    }
}
