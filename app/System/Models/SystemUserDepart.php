<?php

namespace app\System\Models;

use Dux\Database\Attribute\AutoMigrate;
use Dux\Database\Model;
use Illuminate\Database\Schema\Blueprint;

#[AutoMigrate]
class SystemUserDepart extends Model {

    public $table = "system_user_depart";

    public $timestamps = false;

    public function migration(Blueprint $table)
    {
        $table->integer('depart_id');
        $table->integer('user_id');
    }

}