<?php

namespace App\Services;

use App\Repositories\SearchRepository\SearchRepositoryInterface;

class SearchService extends BaseService
{
    protected $searchRepository;

    public function __construct(SearchRepositoryInterface $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    public function search($request)
    {
        return $this->execute(function () use ($request) {
            return $this->searchRepository->search($request);
        }, 'SearchService::search');
    }

    public function filter($request)
    {
        return $this->execute(function () use ($request) {
            return $this->searchRepository->filter($request);
        }, 'SearchService::filter');
    }

    public function autocomplete($keyword)
    {
        return $this->execute(function () use ($keyword) {
            return $this->searchRepository->autocomplete($keyword);
        }, 'SearchService::autocomplete');
    }

    public function nearby($request)
    {
        return $this->execute(function () use ($request) {
            return $this->searchRepository->nearby($request);
        }, 'SearchService::nearby');
    }
}
