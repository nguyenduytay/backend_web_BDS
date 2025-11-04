<?php

namespace App\Services;

use App\Repositories\SearchRepository\SearchRepository;
use App\Repositories\SearchRepository\SearchRepositoryInterface;
use Exception;

class SearchService
{
    protected $searchRepository;

    public function __construct(SearchRepositoryInterface $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    public function search($request)
    {
        try {
            return $this->searchRepository->search($request);
        } catch (Exception $e) {
            return null;
        }
    }

    public function filter($request)
    {
        try {
            return $this->searchRepository->filter($request);
        } catch (Exception $e) {
            return null;
        }
    }

    public function autocomplete($keyword)
    {
        try {
            return $this->searchRepository->autocomplete($keyword);
        } catch (Exception $e) {
            return null;
        }
    }

    public function nearby($request)
    {
        try {
            return $this->searchRepository->nearby($request);
        } catch (Exception $e) {
            return null;
        }
    }
}
