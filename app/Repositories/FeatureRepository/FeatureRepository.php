<?php

namespace App\Repositories\FeatureRepository;

use App\Models\Feature;
use App\Repositories\BaseRepository;

class FeatureRepository extends BaseRepository implements FeatureRepositoryInterface
{
    public function getModel(){
        return Feature::class;
    }
}
