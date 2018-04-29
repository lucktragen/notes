<?php
/**
 * Created by PhpStorm.
 * User: sowhy
 * Date: 2018/4/29
 * Time: 17:38
 */
class Response{

    const JSON = 'json';

    /**
     * 综合方式输出通信数据
     * @param integer $code 状态码
     * @param string $message   提示信息
     * @param array $data   数据
     * @param string $type 数据类型
     * return string
     */
    public static function show($code,$message='',$data=array(),$type=self::JSON){
        if (!is_numeric($code)){
            return '';
        }

        $type = isset($_GET['format']) ? $_GET['format'] : self::JSON;

        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );

        if ($type == 'json'){
            self::json($code,$message,$data);
            exit;
        } elseif($type == 'array') {
            var_dump($result);
        } elseif($type == 'xml'){
            self::xmlEncode($code,$message,$data);
            exit;
        } else {
            // TODO
        }
    }

    /**
     *  按JSON方式输出通信数据
     * @param integer $code 状态码
     * @param string $message   提示信息
     * @param array $data   数据
     * return string
     */
    public function json($code,$message='',$data=array()){
        if (!is_numeric($code)){
            return '';
        }

        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );

        echo json_encode($result);
        exit;
    }

    public static function xml(){
        header("content-type:text/xml");
        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<root>\n";
        $xml .="<code>200</code>\n";
        $xml .="<message>数据返回成功</message>\n";
        $xml .="<data>\n";
        $xml .="<id>1</id>\n";
        $xml .="<name>ceshi</name>\n";
        $xml .="</data>\n";
        $xml .= "</root>\n";

        echo $xml;
    }

    public static function xmlEncode($code,$message,$data=array()){
        if(!is_numeric($code)){
            return;
        }
        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );
        header("Content-Type:text/xml");
        $xml="<?xml version='1.0' encoding='UTF-8'?>";
        $xml .="<root>\n";
        $xml .= self::xmlToEncode($result);
        echo $xml .="</root>";
    }

    public static function xmlToEncode($data){
        $xml = $attr = '';
        foreach($data as $key => $value){
            if(is_numeric($key)){
                $attr = " id='{$key}'";
                $key = 'item';
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= is_array($value)?self::xmlToEncode($value):$value;  //递归，如果value是数组，递归输出节点。
            $xml .= "</{$key}>\n";
        }
        return $xml;
    }

}