<?php
namespace App\Helpers;

class DropdownHelper
{
    /**
     * @param array $header ex. [value => name] || ["" => "All"]
     * @return array
     */
    public static function get_groups($header = null){
        $result = \App\Models\TblRoles::where('level', '<', \Redis::hget('group:'.session('user.role_id'), 'level'))
                    ->orderBy('level','desc')->lists('name','id')->toArray();

        if ($header != null) $result = $header + $result;

        return $result;
    }

    /**
     * @param array $header ex. [value => name] || ["" => "All"]
     * @return array
     */
    public static function get_pool_groups($header = null){
        $result = \App\Models\TblPoolGroups::lists('name','id')->toArray();
        if ($header != null) $result = $header + $result;

        return $result;
    }

    public static function get_aggregators($header = null, $type = 'sms'){
        $result = \App\Models\TblAggregators::where('status', 'enabled')->where('type', $type)->lists('username','id')->toArray();
        if ($header != null) $result = $header + $result;

        return $result;
    }

    public static function get_pools($header = null){
        $result = \App\Models\TblPools::where('status', 'active')->lists('code','id')->toArray();
        if ($header != null) $result = $header + $result;

        return $result;
    }

    public static function get_countries(){
        $result = \App\Models\TblCountries::lists('name', 'code');
        return $result;
    }
}