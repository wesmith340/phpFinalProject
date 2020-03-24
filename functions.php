<?php
    function createHeader(){
        $background = '#383949';
        $headerColor = '#EC0C36';
        $textColor = '#ffffff';
        echo '<!DOCTYPE html>
            <html lang="en"/>
            <head>
                <meta charset="utf-8">
                <link   href="css/bootstrap.min.css" rel="stylesheet">
                <link   href="css/jquery.timepicker.min.css" rel="stylesheet">
                <script type="text/javascript" src="js/bootstrap.min.js"></script>
                <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
                <script type="text/javascript"src="js/jquery.timepicker.min.js"></script>
            <style>
                 body {background-color:'.$background.';}
                .table-borderless td,
                .table-borderless th {background-color:'.$background.'; border: 0;}
                .headerColor {color:'.$headerColor.';}
                .div {background-color:'.$background.';}
                .bodyText{color:'.$textColor.';}
                .btnMargin{margin-right: 10px;}
                .inputPadding{padding-top: 50px;}
                .textarea {
                    clear:left;
                    min-width: 400px;
                    max-width: 400px;
                    min-height:150px;
                    max-height:150px;
                }
            </style>
        </head>
        ';
    }
    function console_log( $data ){
       echo '<script>';
       echo 'console.log('. json_encode( $data ) .')';
       echo '</script>';
    }