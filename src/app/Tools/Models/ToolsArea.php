<?php

declare(strict_types=1);

namespace App\Tools\Models;

use Dux\Database\Attribute\AutoMigrate;

#[AutoMigrate]
class ToolsArea extends \Dux\Database\Model
{
    public $table = 'tools_area';

    public $timestamps = false;

    public function migration(\Illuminate\Database\Schema\Blueprint $table)
    {
        $table->id();
        $table->char("parent_code")->default(0);
        $table->char("code")->default(0);
        $table->string("name");
        $table->integer("level");
        $table->boolean("leaf")->default(true);
    }
}
