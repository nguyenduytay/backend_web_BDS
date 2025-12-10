<?php

namespace App\Services;

use App\Repositories\FeatureRepository\FeatureRepositoryInterface;
use Illuminate\Http\Request;
use Throwable;

class FeatureService extends BaseService
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
        } catch (Throwable $e) {
            $this->handleException($e, 'FeatureService::getAllFeatures');
            return null;
        }
    }

    public function SearchId($id)
    {
        try {
            return $this->featureRepository->find($id);
        } catch (Throwable $e) {
            $this->handleException($e, 'FeatureService::SearchId');
            return null;
        }
    }

    public function create(Request $request)
    {
        try {
            return $this->featureRepository->create($request->all());
        } catch (Throwable $e) {
            $this->handleException($e, 'FeatureService::create');
            return null;
        }
    }

    public function update(Request $request, $id)
    {
        try {
            return $this->featureRepository->update($id, $request->all());
        } catch (Throwable $e) {
            $this->handleException($e, 'FeatureService::update');
            return null;
        }
    }

    public function delete($id)
    {
        try {
            return $this->featureRepository->delete($id);
        } catch (Throwable $e) {
            $this->handleException($e, 'FeatureService::delete');
            return null;
        }
    }
}
