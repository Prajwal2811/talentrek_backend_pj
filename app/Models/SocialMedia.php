<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    protected $table = 'social_media'; // explicitly define table name

    protected $fillable = [
        'media_name',
        'icon_class',
        'media_link',
    ];
}
