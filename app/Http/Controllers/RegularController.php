<?php


namespace App\Http\Controllers;


class RegularController
{

    public function index()
    {
        $list = $this->loadPermission();
        print_r("<pre>");
        print_r($list);
    }

    public function loadPermission()
    {

        $data = $this->read((dirname(app_path()) . "/routes/admin.php"));

        $list = [];
        $p = -1;
        $pp = -1;
        $rute_prefix = "/";
        for ($x = 0; $x <= count($data) - 1; $x++) {
            $one = "/(\s){0,}Route\:\:prefix\((\s){0,}('|\")(\w+)+('|\")(\s){0,}\)->group(\(\w+)(\s){0,}\(\)(\s){0,}\{(\s){0,}##[\x{4e00}-\x{9fa5}]+/u";
            $two = "/->group(\(\w+)(\s){0,}\(\)(\s){0,}\{(\s){0,}###[\x{4e00}-\x{9fa5}]+/u";
            $three = "/(post\(|get\()('|\")(\w+)+('|\"),(\s){0,}('|\")(\w+)+@(\w+)+('|\")\);\/\/[\x{4e00}-\x{9fa5}]+/u";
            $four = "/(post\(|get\()('|\")(\w+)+('|\"),(\s){0,}('|\")(\w+)+@(\w+)+('|\")\)->withoutMiddleware\((\s){0,}('|\")(\w+)+\:(\w+)+('|\")(\s){0,}\);\/\/[\x{4e00}-\x{9fa5}]+/u";

            if (preg_match_all($one, $data[$x], $group_arr)) {
                $p++;
                $pos = stripos($group_arr[0][0], "#");
                $title = substr($group_arr[0][0], $pos + 2);
                $list[$p]["title"] = $title;
                $rute_prefix = "/system/" . $group_arr[4][0] . "/";
            } else if (preg_match_all($two, $data[$x], $group_arr)) {
                $pp++;
                $pos = stripos($group_arr[0][0], "#");
                $title = substr($group_arr[0][0], $pos + 3);
                $list[$p]["child"][$pp]['title'] = $title;
            } else if (preg_match_all($three, $data[$x], $group_arr)) {
                $pos = stripos($group_arr[0][0], "/");
                $title = substr($group_arr[0][0], $pos + 2);

                $route_reg = "/(post\(|get\()('|\")(\w+)+('|\")/";
                preg_match_all($route_reg, $data[$x], $reg_arr);
                $rute = $reg_arr[3][0] ? $reg_arr[3][0] : '';

                $list[$p]["child"][$pp]["child"][$x]['title'] = $title;
                $list[$p]["child"][$pp]["child"][$x]['route'] = $rute_prefix . $rute;
            } else if (preg_match_all($four, $data[$x], $group_arr)) {

                $route_reg = "/(post\(|get\()('|\")(\w+)+('|\")/";
                preg_match_all($route_reg, $data[$x], $reg_arr);

                $pos = stripos($group_arr[0][0], "/");
                $title = substr($group_arr[0][0], $pos + 2);

                $route_reg = "/(post\(|get\()('|\")(\w+)+('|\")/";
                preg_match_all($route_reg, $data[$x], $reg_arr);
                print_r($reg_arr[3][0]);
                $rute = $reg_arr[3][0] ? $reg_arr[3][0] : '';
                $list[$p]["child"][$pp]["child"][$x]['title'] = $title;
                $list[$p]["child"][$pp]["child"][$x]['route'] = $rute_prefix . $rute;

            };
        }

        return $list;

    }


    function read($path)
    {
        $file = @fopen($path, "r+ mode") or exit("Unable to open file!");
        $user = array();
        $i = 0;
        //输出文本中所有的行，直到文件结束为止。
        while (!feof($file)) {
            $text = trim(fgets($file));
            if (strlen($text) > 0) {
                $user[$i] = $text;//fgets()函数从文件指针中读取一行
                $i++;
            }
        }
        @fclose($file);
        return $user;
    }

}
