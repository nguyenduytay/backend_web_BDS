<?php

namespace App\Repositories\ContactRepository;

use App\Repositories\BaseRepository;
use App\Models\Contact;

class ContactRepository extends BaseRepository implements ContactRepositoryInterface
{
    public function getModel()
    {
        return Contact::class;
    }
    public function searchContact($request)
    {
        $query = $this->model::query();

        if ($request->id) {
            $query->where('id', $request->id);
        }
        if ($request->name) {
            $query->where('name', 'like', "%{$request->name}%");
        }
        if ($request->phone) {
            $query->where('phone', $request->phone);
        }
        if ($request->email) {
            $query->where('email', $request->email);
        }

        $contacts = $query->get();

        return $contacts;
    }
}
