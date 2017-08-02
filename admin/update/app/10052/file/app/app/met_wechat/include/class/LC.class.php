<?php
defined('IN_MET') or exit('No permission');

class LC{
    public function __construct() {
        global $_M;
    }
    public function save_sql($table) {
        global $_M;
        $type = $_M['form']['LC']['id']?'save':'add';
        foreach ($_M['form']['LC'] as $key => $val) {
            if ($key != 'id') {
                $data .= "{$key} = '{$val}', ";
            }
        }
        $data = rtrim($data, ", ");
        switch ($type) {
            case 'save':
                DB::query("UPDATE {$table} SET {$data} WHERE id = {$_M['form']['LC']['id']}");
                break;
            case 'add':
                DB::query("INSERT INTO {$table} SET id = NULL, {$data}");
                break;
        }
    }
    public function save_table($table) {
        global $_M;
        $type = $_M['form']['submit_type'];
        $idlist = explode(",",$_M['form']['allid']);
        foreach($idlist as $val){
            if ($val) {
                switch ($type) {
                    case 'save':
                        $data = '';
                        foreach ($_M['form']['LC'][$val] as $key => $val2) {
                            $data .= "{$key} = '{$val2}', ";
                        }
                        $data = rtrim($data, ", ");
                        if (is_number($val)) {
                            DB::query("UPDATE {$table} SET {$data} WHERE id = {$val}");
                        } else {
                            DB::query("INSERT INTO {$table} SET id = NULL, {$data}");
                        }
                        break;
                    case 'del':
                        if (is_number($val)) {
                            DB::query("DELETE FROM {$table} WHERE id = {$val}");
                        }
                        break;
                }
            }
        }
    }
}