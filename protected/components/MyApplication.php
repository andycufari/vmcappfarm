<?php
class MyApplication extends CWebApplication {
    // ...other code...
    public function getUploadDir() {
        return $this->baseUrl.'/images/uploads/';
    }
}