<?php

namespace App\Entity;

enum Roles {
    case ROOM_MEMBER;
    case ROOM_ADMIN;
    case GROUP_MEMBER;
    case GROUP_ADMIN;
    case ADMIN;
}