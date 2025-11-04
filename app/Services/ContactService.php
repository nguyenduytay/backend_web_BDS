<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Repositories\ContactRepository\ContactRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contact;
use App\Repositories\ContactRepository\ContactRepositoryInterface;
use Exception;

class ContactService
{
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function getAllContacts()
    {
        try {
            $contacts = $this->contactRepository->all();
            return $contacts;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function search($request)
    {
        try {
            $data = $this->contactRepository->searchContact($request);
            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function create(Request $request)
    {
        try {
            $status = $this->contactRepository->create($request->all());
            return $status;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $updated = $this->contactRepository->update($id, $request->all());
            return $updated;
        } catch (Exception $e) {
            return null;
        }
    }

    public function delete($id)
    {
        try {
            $status = $this->contactRepository->delete($id);
            return $status;
        } catch (Exception $e) {
            return null;
        };
    }
}
