<?php

namespace App\Models;

use App\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Http;

class UserProfile extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'role' => UserRoleEnum::class,
            'is_active' => 'boolean'
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->isDirty(['latitude', 'longitude'])) {
                $latitude = $model->latitude;
                $longitude = $model->longitude;

                $response = Http::get('https://geocode-maps.yandex.ru/v1/', [
                    'apikey' => env('YANDEX_MAP_KEY'),
                    'geocode' => "$longitude,$latitude",
                    'format' => 'json',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $address = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'] ?? null;

                    $model->address = $address;
                }
            }
        });
    }
}
