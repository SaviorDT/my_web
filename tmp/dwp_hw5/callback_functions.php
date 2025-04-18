<?php
// 111550008 蔡東霖 第5次作業 12/6
// 111550008 Tony Tsai The Fifth Homework 12/6

require_once "./game_controller.php";

if (isset($_GET['function'])) {
    $function = $_GET['function'];
    $type = $_GET['type'] ?? null;
    $option = $_POST['option'] ?? null;

    // $allowedFunctions = [
    //     'Hall::showEntryPoint_callback',
    //     'Hall::showRule_callback',
    // ];

    if (is_callable($function)) {
        if ($type == 'select_dialog') {
            call_user_func($function, $option);
        }
        elseif ($type == 'double_input_dialog') {
            $input1 = $_POST['input1'] ?? null;
            $input2 = $_POST['input2'] ?? null;
            call_user_func($function, $option, $input1, $input2);
        }
        elseif ($type == 'input_dialog') {
            $input = $_POST['input'] ?? null;
            call_user_func($function, $option, $input);
        }
        elseif ($type == 'call') {
            call_user_func($function);
        }
        else {
            echo "type 錯誤";
        }
    } else {
        echo "方法不存在。";
    }
} else {
    echo "未提供 function";
}
?>