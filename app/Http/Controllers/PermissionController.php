<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return Permission::all();
    }

    public function show(Permission $permission)
    {
        return $permission;
    }

    public function store(Request $request)
    {
        $permission = Permission::create($request->only(['name', 'desc']));
        return response()->json($permission, 201);
    }

    public function update(Request $request, Permission $permission)
    {
        $permission->update($request->only(['name', 'desc']));
        return response()->json($permission, 200);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->noContent();
    }

}
