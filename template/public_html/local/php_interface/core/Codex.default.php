<?php

namespace core;

class Codex
{
    const PERMISSION_DENY  = 'D';
    const PERMISSION_READ  = 'R';
    const PERMISSION_WRITE = 'W';
    const PERMISSION_FULL  = 'X';
    const PERMISSION_DOC   = 'U';

    const SITE_ID_MAIN = 's1';

    const GROUP_ID_ADMIN           = 1; //Администраторы
    const GROUP_ID_ALL             = 2; //Все пользователи (в том числе неавторизованные)
    const GROUP_ID_RATE_RIGHT      = 3; //Пользователи, имеющие право голосовать за рейтинг
    const GROUP_ID_AUTHORITY_RIGHT = 4; //Пользователи имеющие право голосовать за авторитет

    const TYPE_SYSTEM  = 'SYSTEM';
    const TYPE_CONTENT = 'CONTENT';
    const TYPE_CATALOG = 'CATALOG';
}
