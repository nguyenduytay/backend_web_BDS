<?php

namespace App\Repositories\PropertyImageRepository;

use App\Models\PropertyImage;
use App\Repositories\RepositoryInterface;

interface PropertyImageRepositoryInterface extends RepositoryInterface
{
    public function allImages($propertyId);
    public function detailPropertyImage($propertyId, $imageId);
    public function getAllHomeAvatars();
}
