<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'artist_id',
        'album_id'
    ];

    public function album()
	{
	  return $this->belongsTo(Album::class);
	}

    public function artist()
	{
	  return $this->belongsTo(Artist::class);
	}
}
