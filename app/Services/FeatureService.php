<?php

namespace App\Services;

use App\Repositories\FeatureRepository\FeatureRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Feature;
use App\Repositories\FeatureRepository\FeatureRepositoryInterface;

class FeatureService
{
    protected $featureRepository;

    public function __construct(FeatureRepositoryInterface $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    public function getAllFeatures()
    {
        try {
            return $this->featureRepository->all();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function SearchId($id)
    {
        try {
            $feature = $this->featureRepository->find($id);
            return $feature;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function create(Request $request)
    {
        try {
            $feature = $this->featureRepository->create($request->all());
            return $feature;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $updated = $this->featureRepository->update($id, $request->all());
            return $updated;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function delete($id)
    {
        try {
            $status = $this->featureRepository->delete($id);
            return $status;
        } catch (\Exception $e) {
            return null;
        }
    }
}
