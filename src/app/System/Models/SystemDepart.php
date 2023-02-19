<?php

namespace App\System\Models;

use Dux\Database\Attribute\AutoMigrate;
use Dux\Database\Model;
use Dux\Database\TreeTrait;
use Illuminate\Database\Schema\Blueprint;

#[AutoMigrate]
class SystemDepart extends Model {

    use TreeTrait;

    public $table = "system_depart";

    public array $treeFields = ["id", "parent_id", "name"];

    public function migration(Blueprint $table) {
        $table->id();
        $table->integer('parent_id')->nullable();
        $table->string('name');
        $table->timestamps();
    }


}