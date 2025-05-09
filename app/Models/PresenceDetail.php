<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresenceDetail extends Model
{
    protected $filltable = ['presence_id', 'nama', 'jabatan', 'asal_instansi', 'tanda_tangan'];
}
