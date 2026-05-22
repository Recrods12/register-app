<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $category
 * @property int $price
 * @property int $stock
 * @property string $status
 * @property string|null $description
 * @property string|null $image
 * @method static Builder<static> query()
 * @method static static|null find(mixed $id, array|string $columns = ['*'])
 */
class Product extends Model
{
    public const CATEGORIES = [
        'Elektronik',
        'Fashion',
        'Aksesoris',
        'Rumah Tangga',
        'Kesehatan',
        'Umum',
    ];

    public const STATUSES = [
        'available' => 'Tersedia',
        'out_of_stock' => 'Habis',
        'preorder' => 'Preorder',
    ];

    protected $fillable = ['name', 'category', 'price', 'stock', 'status', 'description', 'image'];
}
