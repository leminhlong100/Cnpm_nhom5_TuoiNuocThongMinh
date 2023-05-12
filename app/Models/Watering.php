<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Watering extends Model
{
    use HasFactory;

    protected $table = "watering";

    protected $fillable = ['status', 'timer_on', 'timer_off'];

    public function controls()
    {
        return $this->belongsTo(Control::class, 'id_watering', 'id');
    }

    public $timestamps = false;
}
