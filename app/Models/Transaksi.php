<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';
    protected $fillable = ['tanggal', 'id_master_coa', 'deskripsi', 'debit', 'kredit'];

    public function masterCoa()
    {
        return $this->belongsTo(MasterCoa::class, 'id_master_coa');
    }
}
