<?php

namespace App\Enums;

enum RoleEnum: int {
    case superadmin = 1;
    case admin = 2;
    case user = 3;
}
