<?php

namespace LaravelDoctor\Enums;

enum CheckStatus: string
{
    case Pass = 'pass';
    case Fail = 'fail';
    case Warning = 'warning';
}
