<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workflow_etape extends Model
{
    use HasFactory;

    protected $fillable = ['workflow_id','niveau','libelle','user_id'];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');

    }
}
