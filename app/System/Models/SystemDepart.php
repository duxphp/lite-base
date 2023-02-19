<?php

namespace App\System\Models;

use Dux\Database\Attribute\AutoMigrate;
use Dux\Database\Model;
use Illuminate\Database\Schema\Blueprint;
use Kalnoy\Nestedset\NestedSet;
use Kalnoy\Nestedset\NodeTrait;

#[AutoMigrate]
class SystemDepart extends Model {

    public $table = "system_depart";

    use NodeTrait;
    public function migration(Blueprint $table) {
        $table->id();
        $table->string('name');
        NestedSet::columns($table);
        $table->timestamps();
    }


}