<?php

namespace Ades4827\Sprintflow\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    protected array $permissions_seeder = [];
    protected array $permission_readable_names = [];
    protected array $roles_seeder = [];
    protected array $role_readable_names = [];

    public function __construct()
    {
        $this->permissions_seeder = config('sprintflow.permissions_seeder');
        $this->permission_readable_names = config('sprintflow.permission_readable_names');
        $this->roles_seeder = config('sprintflow.roles_seeder');
        $this->role_readable_names = config('sprintflow.role_readable_names');
        if(config('sprintflow.compatibility')) {
            $this->importOldPermission();
        }
    }

    private function getRoleReadableName($role_name)
    {
        if (isset($this->role_readable_names[$role_name])) {
            return $this->role_readable_names[$role_name];
        }

        return Str::title($role_name);
    }

    private function getPermissionReadableName($permission_name)
    {
        if (isset($this->permission_readable_names[$permission_name])) {
            return $this->permission_readable_names[$permission_name];
        }

        return Str::title($permission_name);
    }

    public function refreshDatabase()
    {
        $report['roles'] = $this->syncRoles();
        $report['permissions'] = $this->syncPermissions();
        //app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $this->syncRolePermissions();
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return $report;
    }

    private function syncRolePermissions()
    {
        foreach ($this->permissions_seeder as $guard_name => $permission_guard) {
            $role_permissions = [];
            foreach ($permission_guard as $permission_group_name => $permission_groups) {
                foreach ($permission_groups as $permission => $roles) {
                    $permission_name = implode('.', [$permission_group_name, $permission]);
                    foreach ($roles as $role_name) {
                        $role_permissions[$role_name][] = $permission_name;
                    }
                }
            }

            foreach ($role_permissions as $role_name => $permissions) {
                $role = config('permission.models.role')::where('guard_name', $guard_name)->where('name', $role_name)->first();
                $role->syncPermissions($permissions);
            }
        }
    }

    private function syncPermissions()
    {
        $permission_actions = [];
        foreach ($this->permissions_seeder as $guard_name => $permission_guard) {
            $permission_actions[$guard_name] = [];

            // get current permission list
            $current_permissions = config('permission.models.permission')::where('guard_name', $guard_name)->get()->pluck('id', 'name')->toArray();

            // update permissions
            foreach ($permission_guard as $permission_group_name => $permission_groups) {
                foreach ($permission_groups as $permission => $roles) {
                    // check permission
                    $permission_name = implode('.', [$permission_group_name, $permission]);
                    if (! array_key_exists($permission_name, $permission_actions[$guard_name])) {

                        $action = 'insert';
                        $id = null;
                        // if permission already exist
                        if (array_key_exists($permission_name, $current_permissions)) {
                            $action = 'skip';
                            $id = $current_permissions[$permission_name];
                            unset($current_permissions[$permission_name]);
                        } else {
                            // insert new permission
                            $new_permission = config('permission.models.permission')::create([
                                'name' => $permission_name,
                                'guard_name' => $guard_name,
                            ]);
                            $id = $new_permission->id;
                        }

                        $permission_actions[$guard_name][$action][] = [
                            'permission_name' => $permission_name,
                            'id' => $id,
                        ];
                    }
                }
            }
            // permission to remove
            foreach ($current_permissions as $permission_name => $permission_id) {
                $permission_actions[$guard_name]['delete'][] = [
                    'permission_name' => $permission_name,
                    'id' => $permission_id,
                ];
                // delete permission
                $permission = config('permission.models.permission')::where('name', $permission_name)->first();
                $permission->syncRoles([]);
                $permission->delete();
                unset($current_permissions[$permission_name]);
            }

            //dd($this->permissions_seeder, $current_permissions, $permission_actions);
        }
        // Make report table
        $permission_report = [
            'headers' => ['Permission', 'Guard', 'Status'],
            'rows' => []
        ];
        foreach ($permission_actions as $guard => $permission_action) {
            foreach ($permission_action as $status => $permissions) {
                foreach ($permissions as $permission) {
                    $permission_report['rows'][] = [
                        $permission['permission_name'],
                        $guard,
                        $status,
                    ];
                }
            }
        }
        return $permission_report;
    }

    private function syncRoles()
    {
        $role_actions = [];
        foreach ($this->permissions_seeder as $guard_name => $permission_guard) {
            // get current role list
            $current_roles = config('permission.models.role')::where('guard_name', $guard_name)->get()->pluck('id', 'name')->toArray();

            // update roles
            $all_roles = [];
            if( isset($this->roles_seeder[$guard_name]) ) {
                $all_roles = $this->roles_seeder[$guard_name];
            }
            foreach ($permission_guard as $permission_group_name => $permission_groups) {
                foreach ($permission_groups as $permission => $roles) {
                    foreach ($roles as $role_name) {
                        if (! in_array($role_name, $all_roles)) {
                            $all_roles[] = $role_name;
                        }
                    }
                }
            }
            foreach ($all_roles as $role_name) {
                $action = 'insert';
                $id = null;
                if (array_key_exists($role_name, $current_roles)) {
                    $action = 'skip';
                    $id = $current_roles[$role_name];
                    config('permission.models.role')::where('name', $role_name)->where('guard_name', $guard_name)->update(['readable_name' => $this->getRoleReadableName($role_name)]);
                    unset($current_roles[$role_name]);
                } else {
                    // insert new role
                    $new_role = config('permission.models.role')::create([
                        'name' => $role_name,
                        'readable_name' => $this->getRoleReadableName($role_name),
                        'guard_name' => $guard_name,
                    ]);
                    $id = $new_role->id;
                }

                $role_actions[$guard_name][$action][] = [
                    'role_name' => $role_name,
                    'id' => $id,
                ];
            }
            // role to remove
            foreach ($current_roles as $role_name => $role_id) {
                $role_actions[$guard_name]['delete'][] = [
                    'role_name' => $role_name,
                    'id' => $role_id,
                ];
                // delete permission
                $role = config('permission.models.role')::where('name', $role_name)->where('guard_name', $guard_name)->first();
                $role->delete();
                unset($current_roles[$role_name]);
            }

            //dd($this->permissions_seeder, $current_roles, $role_actions);
        }
        // Make report table
        $role_report = [
            'headers' => ['Role', 'Guard', 'Status'],
            'rows' => []
        ];
        foreach ($role_actions as $guard => $role_action) {
            foreach ($role_action as $status => $roles) {
                foreach ($roles as $role) {
                    $role_report['rows'][] = [
                        $role['role_name'],
                        $guard,
                        $status,
                    ];
                }
            }
        }
        return $role_report;
    }

    private function importOldPermission()
    {
        $crud = [
            'create',
            'update',
            'delete',
            'view',
        ];

        /**
         * array first level: first permission token
         * array second level: permission role
         * array third level: second permission token
         *
         * Ex: $all_crud_permission['settings']['admin'] = 'menu';
         * is access for role "admin" to "settings.menu"
         */
        $all_crud_permission = [];

        foreach ($all_crud_permission as $permission_group_name => $permission_groups) {
            foreach ($permission_groups as $role => $permissions) {
                foreach ($permissions as $permission_name) {
                    if (! isset($this->permissions_seeder[$permission_group_name][$permission_name]) ||
                        (isset($this->permissions_seeder[$permission_group_name][$permission_name]) &&
                            ! in_array($role, $this->permissions_seeder[$permission_group_name][$permission_name]))) {

                        $this->permissions_seeder['admin'][$permission_group_name][$permission_name][] = $role;
                    }
                }
            }
        }
    }
}
