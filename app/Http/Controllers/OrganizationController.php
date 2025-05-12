<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function show(Organization $organization)
    {
        $this->authorize('view', $organization); // Si usas policies
        return view('organizations.show', compact('organization'));
    }
}
