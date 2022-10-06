<?php

namespace WendellAdriel\LaravelHut\Models;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\LaravelHut\Models\Traits\LogChanges;

abstract class BaseModel extends Model
{
    use LogChanges;
}
