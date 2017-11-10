<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public static function getTreeData()
    {
        if(is_administrator()){
            $data = self::where('status', 1)->where('level', '>', 1)->get()->toArray();
        }else{
            $company_id = get_user_company_id();
            $data = self::where([
                ['status','=',1],['level','>',1]
            ])->where(function ($query) use ($company_id) {
                $query->where('company_id', '=', $company_id)
                    ->orWhere('id', '=', $company_id);
            })->get()->toArray();
        }
        return formatTreeData($data, 'id', 'parent_id', headquarters('id'));
    }

    public static function info($id, $field = true)
    {
        $info = self::find($id);
        if ($info) {
            if ($field === true) {
                return $info;
            } else {
                return isset($info->$field) ? $info->$field : null;
            }
        } else {
            return null;
        }
    }
}
