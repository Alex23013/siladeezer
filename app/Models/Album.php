<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'artist_id'
    ];    

    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function artist()
	{
	  return $this->belongsTo(Artist::class);
	}
}
