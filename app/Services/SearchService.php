<?php

namespace App\Services;

use App\Repositories\SearchRepository\SearchRepositoryInterface;
use Throwable;

class SearchService extends BaseService
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
        } catch (Throwable $e) {
            $this->handleException($e, 'SearchService::search');
            return null;
        }
    }

    public function filter($request)
    {
        try {
            return $this->searchRepository->filter($request);
        } catch (Throwable $e) {
            $this->handleException($e, 'SearchService::filter');
            return null;
        }
    }

    public function autocomplete($keyword)
    {
        try {
            return $this->searchRepository->autocomplete($keyword);
        } catch (Throwable $e) {
            $this->handleException($e, 'SearchService::autocomplete');
            return null;
        }
    }

    public function nearby($request)
    {
        try {
            return $this->searchRepository->nearby($request);
        } catch (Throwable $e) {
            $this->handleException($e, 'SearchService::nearby');
            return null;
        }
    }
}
