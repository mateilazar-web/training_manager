<?php

namespace App\Enums;

enum TeamRoleAssignStatus: string
{
    case Admin = 'Admin';
    case Unassigned = 'Unassigned';
    case RequestPending = 'Pending';
    case Assigned = 'Assigned';
}
