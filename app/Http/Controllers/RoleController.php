<?php

namespace App\Http\Controllers;


use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    public function index()
    {
        Role::all();
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'rank' => 'required|integer',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        return Role::create($validated);
    }
    public function show(Role $role)
    {
        return $role;
    }
    public function update(Request $request, Role $role)
    {
        $role->update($request->only(['name', 'rank', 'permission_id']));
        return $role;
    }

}
