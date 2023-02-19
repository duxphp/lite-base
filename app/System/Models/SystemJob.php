<?php

namespace app\System\Models;

use Dux\Database\Attribute\AutoMigrate;
use Dux\Database\Model;
use Illuminate\Database\Schema\Blueprint;

#[AutoMigrate]
class SystemJob extends Model {

    public $table = "system_job";

    public function migration(Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    }

}