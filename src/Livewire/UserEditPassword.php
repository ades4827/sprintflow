<?php

namespace Ades4827\Sprintflow\Livewire;

use Ades4827\Sprintflow\Traits\LivewireUtilsTrait;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UserEditPassword extends Component
{
    use LivewireUtilsTrait;

    public $is_modal = false;

    #[Locked]
    public $user_id;

    public $state = [];

    protected function rules()
    {
        return [
            'state.current_password' => ['required'],
            'state.password' => [
                'required',
                Password::min(6)->letters()->mixedCase()->numbers()->uncompromised(),
            ],
            'state.password_confirmation' => 'required|same:state.password',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'state.current_password' => __('sprintflow::fields.current_password'),
            'state.password' => __('sprintflow::fields.new_password'),
            'state.password_confirmation' => __('sprintflow::fields.repeat_password'),
        ];
    }

    public function submit()
    {
        $this->validate();

        $this->checkPermission('users.password');

        $user = auth()->user();

        try {
            if (!Hash::check($this->state['current_password'], $user->password)) {
                $this->addError('current_password', __('admin.error_message.wrong_password'));
                return;
            }

            if (isset($this->state['password']) && strlen($this->state['password']) > 0) {
                $user->password = Hash::make($this->state['password']);
            }
            $user->save();

            $route_name = 'admin.users.editPassword';
            $return_message = __('admin.users.password_confirm_update');
            if ($this->is_modal) {
                $route_name = null;
            }

            return $this->return($return_message, $route_name);
        } catch (Exception $e) {
            report($e);
            $this->addError('exception', $e->getMessage());
        }
    }

    public function render()
    {
        return view('sprintflow::livewire.user-edit-password');
    }
}
