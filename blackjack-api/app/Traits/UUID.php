<?php
// FINALLY AND FOR NOW, THIS TRAIT IS NOT NEEDED, I'LL LEAVE IT IN CASE I NEED IT LATER.
namespace App\Traits;

use Illuminate\Support\Str;

trait UUID
{
    protected static function boot ()
    {
        // Boot other traits on the Model
        parent::boot();

        /**
         * Listen for the creating event on the user model.
         * Sets the 'id' to a UUID using Str::uuid() on the instance being created
         */
        static::creating(function ($model) {
            if ($model->getKey() === null) {
                $model->setAttribute('uuid', Str::uuid()->toString());
            }
        });
    }

    // Tells the database not to auto-increment this field
    public function getIncrementing ()
    {
        return false;
    }

    // Helps the application specify the field type in the database
    public function getKeyType ()
    {
        return 'string';
    }
}