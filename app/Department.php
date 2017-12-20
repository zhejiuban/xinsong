<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public static function getTreeData($type = 1, $is_admin = 0)
    {
        if (is_administrator() || check_user_role(null,'总部管理员') || $is_admin) {
            if ($type == 1) {
                $data = self::where('status', 1)->get()->toArray();
            } else {
                $data = self::where('status', 1)->where('level', '<=', 2)->get()->toArray();
            }
        } else {
            $company_id = get_user_company_id();
            if ($type == 1) {
                $data = self::where([
                    ['status', '=', 1]
                ])->where(function ($query) use ($company_id) {
                    $query->where('company_id', '=', $company_id)
                        ->orWhere('id', '=', $company_id);
                })->get()->toArray();
            } else {
                $data = self::where([
                    ['status', '=', 1], ['level', '<=', 2], ['id', '=', $company_id]
                ])->get()->toArray();
            }
        }
        return formatTreeData($data, 'id', 'parent_id', 0);
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
