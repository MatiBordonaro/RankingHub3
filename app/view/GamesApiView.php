<?php

    class GamesApiView{
        public function response($data, $status){
            header('Content-type: application/json');
            header('HTTP/1.1 '.$status." ".$this->_requestStatus($status));
            echo json_encode($data);
            var_dump($status);
        }
        private function _requestStatus($code){            
            $status = array(
                200 => 'OK',
                201 => 'CREATED',
                404 => 'NOT FOUND',
                500 => 'Internal Server Error'
            );
            return (isset($status[$code])) ? $status[$code] : $status[500];  
        }
    }
