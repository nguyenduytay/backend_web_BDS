<?php

namespace App\Services;

use App\Repositories\FeatureRepository\FeatureRepositoryInterface;
use Illuminate\Http\Request;

class FeatureService extends BaseService
{
    protected $featureRepository;

    public function __construct(FeatureRepositoryInterface $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    public function getAllFeatures()
    {
        return $this->execute(function () {
            return $this->featureRepository->all();
        }, 'FeatureService::getAllFeatures');
    }

    public function SearchId($id)
    {
        return $this->execute(function () use ($id) {
            return $this->featureRepository->find($id);
        }, 'FeatureService::SearchId');
    }

    public function create(Request $request)
    {
        return $this->execute(function () use ($request) {
            return $this->featureRepository->create($request->all());
        }, 'FeatureService::create');
    }

    public function update(Request $request, $id)
    {
        return $this->execute(function () use ($request, $id) {
            return $this->featureRepository->update($id, $request->all());
        }, 'FeatureService::update');
    }

    public function delete($id)
    {
        return $this->execute(function () use ($id) {
            return $this->featureRepository->delete($id);
        }, 'FeatureService::delete');
    }
}
