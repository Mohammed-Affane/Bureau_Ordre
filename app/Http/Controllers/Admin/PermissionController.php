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
        Permission::updateOrCreate(
            ['id' => $request->permission_id],
            ['name' => $request->name]
        );        

        return response()->json(['success'=>'Permission saved successfully.']);
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
     * Delete permission
     */
    public function destroy($id)
    {
        Permission::find($id)->delete();
        return response()->json(['success'=>'Permission deleted successfully.']);
        
    }
}
