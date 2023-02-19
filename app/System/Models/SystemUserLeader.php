<?php

namespace app\System\Models;

use Dux\Database\Attribute\AutoMigrate;
use Dux\Database\Model;
use Illuminate\Database\Schema\Blueprint;

#[AutoMigrate]
class SystemUserLeader extends Model {

    public $table = "system_user_leader";

    public $timestamps = false;

    public function migration(Blueprint $table)
    {
        $table->integer('leader_id');
        $table->integer('user_id');
    }

}