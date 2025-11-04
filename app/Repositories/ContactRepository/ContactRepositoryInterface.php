<?php

namespace App\Repositories\ContactRepository;

use App\Models\Contact;
use App\Repositories\RepositoryInterface;

interface ContactRepositoryInterface extends RepositoryInterface
{
    public function searchContact($request);
}

