<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions
     */
    public function index(Request $request)
    {
        if ($request->expectsJson() || $request->query('ajax') == 1) {
            $perPage = $request->query('per_page', 10);  // default 10 per page
            $permissions = Permission::orderBy('id', 'desc')->paginate($perPage);
            
            return response()->json($permissions);
        }
        
        return view('dashboards.admin.permissions.index');
    }
    
    /**
     * Store a newly created permission
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name'
        ]);
        
        Permission::create(['name' => $request->name]);
        
        return response()->json(['success' => 'Permission created successfully.']);
    }
    
    /**
     * Edit permission
     */
    public function edit($id)
    {
        $permission = Permission::find($id);
        return response()->json($permission);
    }
    
    /**
     * Update the specified permission
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id
        ]);
        
        $permission = Permission::findOrFail($id);
        $permission->update(['name' => $request->name]);
        
        return response()->json(['success' => 'Permission updated successfully.']);
    }
    
    /**
     * Delete permission
     */
    public function destroy($id)
    {
        Permission::findOrFail($id)->delete();
        return response()->json(['success' => 'Permission deleted successfully.']);
    }
}