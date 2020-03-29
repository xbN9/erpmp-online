<?php
//include 'Classes/PHPExcel/IOFactory.php';
//include_once 'Classes/PHPExcel/Reader/Excel5.php';
//include_once 'Classes/PHPExcel/Writer/Excel5.php';

class TestController extends Yaf\Controller_Abstract {

    function indexAction() {

        $PHPExcel = new PHPExcel();
        //$filename = "/Users/doifusd-sky/Desktop/20181121.xlsx";
        $filename = "/Users/doifusd-sky/Desktop/3.xls";

        $PHPReader = new \PHPExcel_Reader_Excel5();
        // 载入文件
        $PHPExcel = $PHPReader->load($filename);
        // 获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet(0);
        // 获取总列数
        $allColumn = $currentSheet->getHighestColumn();
        // 获取总行数
        $allRow = $currentSheet->getHighestRow();

        $data = [];
        // 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
            // 从哪列开始，A表示第一列
            for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
                // 数据坐标
                $address = $currentColumn . $currentRow;
                // 读取到的数据，保存到数组$arr中
                //$data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
                $cell = $currentSheet->getCell($address)->getValue();
                if (is_object($cell)) {
                    $cell = $cell->__toString();
                }
                $data[$currentRow][$currentColumn] = $cell;
            }
            //修改表S1的内容
            //根据实际业务中的内容框修改
        }
        die(print_r($data));
    }

}
