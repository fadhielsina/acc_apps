<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterCoa extends Model
{
    protected $table = 'master_coa';
    protected $fillable = ['kode', 'nama', 'id_master_kategori_coa'];

    public function kategoriCoa()
    {
        return $this->belongsTo(MasterKategoriCoa::class, 'id_master_kategori_coa');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_master_coa');
    }
}
