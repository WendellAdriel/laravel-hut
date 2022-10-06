<?php

namespace WendellAdriel\LaravelHut\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    public const ACTION_CREATE = 'CREATED';
    public const ACTION_UPDATE = 'UPDATED';
    public const ACTION_DELETE = 'DELETED';

    protected $fillable = ['user_id', 'record_id', 'table', 'action', 'message', 'new_data', 'old_data', 'changed_data'];
}
