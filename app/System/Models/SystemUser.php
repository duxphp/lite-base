<?php
declare(strict_types = 1);

namespace app\System\Models;

use Dux\App;
use Dux\Database\Attribute\AutoMigrate;
use Dux\Database\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Connection;
use \Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[AutoMigrate]
class SystemUser extends Model {

    public $table = "system_user";

    public function migration(Blueprint $table)
    {
        $table->id();
        $table->string('username')->unique();
        $table->string('nickname');
        $table->string('password');
        $table->string('avatar')->nullable();
        $table->boolean('leader')->default(false);
        $table->boolean('status')->default(true);
        $table->timestamps();
    }

    public function seed(Connection $db) {
        $db->table($this->table)->insert([
            'username' => 'admin',
            'nickname' => '管理员',
            'password' => password_hash('admin', PASSWORD_DEFAULT),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function roles(): BelongsToMany {
        return $this->belongsToMany(SystemRole::class, 'system_user_role', 'user_id', 'role_id');
    }

    public function departs(): BelongsToMany {
        return $this->belongsToMany(SystemDepart::class, 'system_user_depart', 'user_id', 'depart_id');
    }

    public function leaders(): BelongsToMany {
        return $this->belongsToMany(SystemDepart::class, 'system_user_leader', 'user_id', 'leader_id');
    }

    public function operates(): \Illuminate\Database\Eloquent\Relations\MorphMany {
        return $this->morphMany(LogOperate::class, 'user');
    }

    public function getPermissionAttribute(): array {
        $data = [];
        foreach ($this->roles as $item) {
            $data = [...$data, ...$item->permission];
        }
        return array_filter($data);
    }

}