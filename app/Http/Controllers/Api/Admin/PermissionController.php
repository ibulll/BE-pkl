<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;

class PermissionController extends Controller
{
    //
    public function index()
    {
        $permissions = Permission::when(request()->search, function($permissions) {
            $permissions = $permissions->where('name', 'like', '%'. request()->search . '%');
        })->latest()->paginate(5);

        $permissions->appends(['search' => request()->search]);

        return new PermissionResource(true, 'List Data Permissions', $permissions);
    }

    public function all()
    {
        $permissions = Permission::latest()->get();

        return new PermissionResource(true, 'List Data Permissions', $permissions);
    }
}