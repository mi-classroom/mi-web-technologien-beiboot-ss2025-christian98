<?php

namespace App\Services\Session\Toast;

enum ToastType: string
{
    case Info = 'info';
    case Success = 'success';
    case Warning = 'warning';
    case Error = 'error';
}
