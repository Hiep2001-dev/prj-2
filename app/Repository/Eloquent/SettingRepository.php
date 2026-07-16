<?php

namespace App\Repository\Eloquent;

use App\Models\Setting;

/**
 * Class SettingRepository
 * @package App\Repositories\Eloquent
 */
class SettingRepository extends BaseRepository
{
    /**
     * SettingRepository constructor.
     *
     * @param Setting $setting
     */
    public function __construct(Setting $setting)
    {
        parent::__construct($setting);
    }
}

?>