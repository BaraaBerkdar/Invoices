<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoices_attach extends Model
{
    use HasFactory;

    protected $fillable = [
    	'id'	,'file_name'	,'invoice_number'	,'Created_by'	,'invoice_id'
    ];
}
