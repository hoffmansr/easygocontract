<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Annees_fiscale extends Model
{
    protected $table = 'annees_fiscales';

    protected $fillable = ['annee', 'societe_id'];

    protected $appends = ['debut', 'fin'];

    public function getDebutAttribute()
    {
        return Carbon::createFromDate($this->annee, 1, 1);
    }

    public function getFinAttribute()
    {
        return Carbon::createFromDate($this->annee, 12, 31);
    }
}
