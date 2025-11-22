<?php

namespace App\Services;

use App\Repositories\ContactRepository\ContactRepositoryInterface;
use Illuminate\Http\Request;

class ContactService extends BaseService
{
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function getAllContacts()
    {
        return $this->execute(function () {
            return $this->contactRepository->all();
        }, 'ContactService::getAllContacts');
    }

    public function search($request)
    {
        return $this->execute(function () use ($request) {
            return $this->contactRepository->searchContact($request);
        }, 'ContactService::search');
    }

    public function create(Request $request)
    {
        return $this->execute(function () use ($request) {
            return $this->contactRepository->create($request->all());
        }, 'ContactService::create');
    }

    public function update(Request $request, $id)
    {
        return $this->execute(function () use ($request, $id) {
            return $this->contactRepository->update($id, $request->all());
        }, 'ContactService::update');
    }

    public function delete($id)
    {
        return $this->execute(function () use ($id) {
            return $this->contactRepository->delete($id);
        }, 'ContactService::delete');
    }
}
