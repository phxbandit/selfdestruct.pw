<?php

class getHash {
    public $bytes = 128;

    public function hashPass($password) {
        $random = openssl_random_pseudo_bytes($this->bytes);
        $randPass = $password . $random;
        $sha256 = hash('sha256', $randPass);
        return $sha256;
    }
}

?>
