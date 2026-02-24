<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Folder extends Model
{

    protected $fillable = ['name', 'user_id', 'parent_id',];
    public $timestamps = false;
    // Self-referencing relationship for sub-folders
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
