<?php

namespace App\Enums;

enum UserTeamRole: string
{
    case Owner = 'Owner';
    case Coach = 'Coach';
    case Volunteer = 'Volunteer';
    case Pending = 'Pending';
}
