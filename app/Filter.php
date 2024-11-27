<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    // Especifica la tabla si no sigue el nombre plural del modelo
    // protected $table = 'filters';

    // Relación con el producto
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    // Relación con la categoría
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    // Relación con el usuario (si es que los filtros son gestionados por usuarios)
    public function user()
    {
        return $this->belongsTo('App\User')->withDefault([
            'id' => 0,
            'name' => 'Unknown User'
        ]);
    }

    // Relación con los registros de stock, para conocer la cantidad y desperdicio
    public function stock()
    {
        return $this->hasMany('App\Stock');
    }

    // Relación con la entidad que gestiona la venta, si aplica
    public function sell_details()
    {
        return $this->hasMany('App\SellDetails', 'filter_id');
    }

    // Si necesitas algún campo específico de esta tabla para el filtro, puedes especificarlo
    protected $fillable = [
        'product_id', 'category_id', 'user_id', 'filter_name', 'min_qty', 'max_qty', 'waste_qty'
    ];
}
