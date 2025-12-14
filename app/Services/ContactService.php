<?php

namespace App\Services;

use App\Repositories\ContactRepository\ContactRepositoryInterface;
use Illuminate\Http\Request;
use Throwable;

class ContactService extends BaseService
{
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function getAllContacts()
    {
        try {
            return $this->contactRepository->all();
        } catch (Throwable $e) {
            $this->handleException($e, 'ContactService::getAllContacts');
            return null;
        }
    }

    public function search($request)
    {
        try {
            return $this->contactRepository->searchContact($request);
        } catch (Throwable $e) {
            $this->handleException($e, 'ContactService::search');
            return null;
        }
    }

    public function create(Request $request)
    {
        try {
            return $this->contactRepository->create($request->all());
        } catch (Throwable $e) {
            $this->handleException($e, 'ContactService::create');
            return null;
        }
    }

    public function update(Request $request, $id)
    {
        try {
            return $this->contactRepository->update($id, $request->all());
        } catch (Throwable $e) {
            $this->handleException($e, 'ContactService::update');
            return null;
        }
    }

    public function delete($id)
    {
        try {
            return $this->contactRepository->delete($id);
        } catch (Throwable $e) {
            $this->handleException($e, 'ContactService::delete');
            return null;
        }
    }

    /**
     * User: Lấy thông tin liên hệ của người bán (property owner)
     */
    public function getSellerContact($propertyId)
    {
        try {
            $property = \App\Models\Property::with('contact')->find($propertyId);
            if (!$property) {
                return null;
            }
            return $property->contact;
        } catch (Throwable $e) {
            $this->handleException($e, 'ContactService::getSellerContact');
            return null;
        }
    }
}
