<?php

namespace Ades4827\Sprintflow\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Base controller for the package.
 *
 * It does not depend on the host application's App\Http\Controllers\Controller
 * (which, since Laravel 11, is an empty class without middleware() support).
 * It provides middleware() from Illuminate\Routing\Controller, plus
 * authorize()/authorizeResource() and validate(), like the pre-Laravel 11 base controller.
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
}
