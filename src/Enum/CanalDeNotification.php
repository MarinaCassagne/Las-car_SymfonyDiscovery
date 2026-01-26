<?php 

namespace App\Enum;

enum CanalDeNotification: string {
    case Email = 'Email';
    case Push = 'Push';
    case SMS = 'SMS';

}