<?php

namespace Ades4827\Sprintflow\Livewire;

use Ades4827\Sprintflow\Models\Admin;
use Ades4827\Sprintflow\Models\Role;
use Ades4827\Sprintflow\Traits\LivewireUtilsTrait;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class AdminForm extends Component
{
    use LivewireUtilsTrait;

    public $is_modal = false;

    #[Locked]
    public ?int $model_id = null;
    #[Locked]
    public $roles = [];

    public array $state = [];

    protected function rules()
    {
        $rules = [
            'state.email' => 'required|email|unique:admins,email',
            'state.password' => 'required|min:6',
            'state.roles' => 'required|min:1',
        ];
        if (empty($this->state['company'])) {
            $rules['state.name'] = 'required|string|min:2|max:190';
            $rules['state.surname'] = 'required|string|min:2|max:190';
        } else {
            $rules['state.company'] = 'required|string|min:2|max:190';
        }

        if ($this->model_id !== null) {
            $rules['state.email'] = [
                'required', 'email', Rule::unique('admins', 'email')->ignore(auth('admin')->user()->id)->ignore($this->model_id),
            ];
            $rules['state.password'] = 'min:6';
        }

        return $rules;
    }

    protected function validationAttributes()
    {
        return [
            'state.company' => __('sprintflow::fields.company'),
            'state.name' => __('sprintflow::fields.name'),
            'state.surname' => __('sprintflow::fields.surname'),
            'state.email' => __('sprintflow::fields.email'),
            'state.password' => __('sprintflow::fields.password'),
            'state.roles' => __('sprintflow::fields.roles'),
        ];
    }

    /**
     * @throws Exception
     */
    public function mount()
    {
        $this->checkPermission('admins.view');

        $this->roles = Role::where('guard_name', 'admin')
            ->when(!auth('admin')->user()->hasRole(['admin']), function ($query) {
                $query->notAdmin();
            })->select('readable_name', 'id')->get()->map(function ($item) {
                return [
                    'name' => $item->readable_name,
                    'id' => $item->id,
                ];
            })->toArray();

        if ($this->model_id) {
            $admin = app(Admin::class)::findOrFail($this->model_id);
            $this->state['company'] = $admin->company;
            $this->state['name'] = $admin->name;
            $this->state['surname'] = $admin->surname;
            $this->state['email'] = $admin->email;
            $this->state['roles'] = $admin->roles->pluck('id')->toArray();
            $this->state['is_enabled'] = $admin->is_enabled;
        } else {
            $this->state['company'] = null;
            $this->state['name'] = null;
            $this->state['surname'] = null;
            $this->state['email'] = null;
            $this->state['roles'] = [];
            $this->state['password'] = substr(md5(uniqid(random_int(1, 6), true)), 0, 8);
            $this->state['is_enabled'] = true;
        }
    }

    public function submit()
    {
        // FIX: remove empty roles
        $this->state['roles'] = array_filter($this->state['roles']);

        $this->validate();

        // create
        if ($this->model_id == null) {
            $this->checkPermission('admins.create');
            $admin = app(Admin::class);
        }
        // update
        else {
            $this->checkPermission('admins.update');
            $admin = app(Admin::class)::findOrFail($this->model_id);
        }

        try {
            $admin->company = $this->state['company'];
            $admin->name = $this->state['name'];
            $admin->surname = $this->state['surname'];
            $admin->email = $this->state['email'];
            if (isset($this->state['password']) && $this->state['password'] !== '') {
                $admin->password = Hash::make($this->state['password']);
            }
            $admin->is_enabled = $this->state['is_enabled'];
            $admin->save();
            $admin->roles()->sync($this->state['roles']);

            $route_name = 'admin.admins.index';
            $options = ['table_to_refresh' => '#admins-table'];
            $return_message = 'Utente aggiornato correttamente';
            if ($this->model_id === null) {
                $return_message = 'Utente creato correttamente';
            }
            if ($this->is_modal) {
                $route_name = null;
            }

            return $this->return($return_message, $route_name, $options);
        } catch (Exception $e) {
            report($e);
            $this->addError('exception', $e->getMessage());
        }
    }

    public function render()
    {
        return view('sprintflow::livewire.admin-form');
    }
}
