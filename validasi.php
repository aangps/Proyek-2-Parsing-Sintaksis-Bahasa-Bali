<?php
$conn = mysqli_connect('153.92.10.74', 'u5030462_tbo', 'tbotbo', 'u5030462_tbo');
$res = [];
$data = [];
function sentenceValidation($input)
{
    global $conn;
    global $res;
    if (empty($input)) {
        return $res;
    } else {
        $input2 = explode(' ', $input);
        $query = "SELECT * FROM cnf WHERE body LIKE BINARY '" . $input2[0] . "%'";
        $result = mysqli_query($conn, $query);
        $result = mysqli_fetch_assoc($result);
        if (is_array($result)) {
            $str = $result['body'];
            $head = $result['head'];
            $temp = $input;
            $tempResult = substr($temp, 0, strlen($str));
            if (strtoupper($str) == strtoupper($tempResult)) {
                array_push($res, $str);
                array_push($res, $head);
                return sentenceValidation(substr($input, strlen($str) + 1));
            } else {
                return sentenceValidation(substr($input, strlen($input2[0]) + 1));
            }
        } else {
            $str = '';
            $head = '';
            array_push($res, $str);
            array_push($res, $head);
            return sentenceValidation(substr($input, strlen($input2[0]) + 1));
        }
    }
}

    
function textValidation($input)
{
    global $res;
    global $data;
    $input = explode(PHP_EOL, $input);
    for ($i = 0;$i<count($input);$i++) {
        $res = [];
        $input[$i] = str_replace('.', '', $input[$i]);
        array_push($data, sentenceValidation($input[$i]));
    }
    return $data;
}

function getSplitText($input)
{
    return explode(PHP_EOL, $input);
}

function setValidator($data)
{
    return explode(PHP_EOL, $data);
}
