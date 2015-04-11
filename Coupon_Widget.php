<?php
/**
 * Description of Coupon_Widget
 * Author: Titan
 */
class Coupon_Widget extends WP_Widget {   
    function __construct() {
        parent::__construct(
            'coupon_widget',
            'Coupon Widget',
            array( 'description' => 'Coupon Widget')
        );
    }
    
    function GET_XLSX_FILE_PATH() {
        return wp_upload_dir()['path'] . '\sampleinputfile.xlsx';
    }
    
    function GET_XLSX_FILE_URL() {
        return "http://localhost/testPhp/dowload/sampleinputfile.xlsx";
    }

    function widget( $args, $instance ) {
        $recordList = $this->get_xlsx_data();    
        
        echo '<aside class="widget widget_search" id="search-2">';
            echo '<div id="couponWidget">';
            foreach ($recordList as $couponItem){
                echo '<div class="couponItem">';
                    echo '<div id="couponLogo">';
                        echo '<img src="'.$couponItem['logo'].'" />';
                    echo '</div>';
                    echo '<div id="coupomTitle">';
                        echo '<a href="'.$couponItem['titleLink'].'" target="_blank" >';
                            echo '<span>'.$couponItem['title'].'</span>';
                        echo '</a>';
                    echo '</div>';
                    echo '<div id="couponDescription">';
                        echo '<span>'.$couponItem['description'].'</span>';
                    echo '</div>';
                    echo '<div id="couponButton">';
                        echo '<a href="'.$couponItem['bttLink'].'" target="_blank" >';
                            echo '<img src="'.$couponItem['bttSource'].'" />';
                        echo '</a>';
                    echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        echo '</aside>';
    }

    //Get data from XLSX file
    function get_xlsx_data() {
        ini_set('memory_limit', '-1');
        set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');         
        $inputFileName = $this->GET_XLSX_FILE_PATH();
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) 
            . '": ' . $e->getMessage());
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $returnData = array();
        for ($row = 4; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, 
            NULL, TRUE, FALSE);
            $record = array();
            foreach($rowData[0] as $k=>$v){
                switch ($k) {
                    case 0:
                        $record["logo"] = $v;
                        break;
                    case 1:
                        $record["title"] = $v;
                        break;
                    case 2:
                        $record["titleLink"] = $v;
                        break;
                    case 3:
                        $record["description"] = $v;
                        break;
                    case 4:
                        $record["bttSource"] = $v;
                        break;
                    case 5:
                        $record["bttLink"] = $v;
                        break;
                    default:
                        break;
                } 
            }
            array_push($returnData, $record);
        }
        return $returnData;
    }
}
