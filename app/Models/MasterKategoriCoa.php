<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKategoriCoa extends Model
{
    protected $table = 'master_kategori_coa';
    protected $fillable = ['nama'];

    public function coa()
    {
        return $this->hasMany(MasterCoa::class, 'id_master_kategori_coa');
    }
}
