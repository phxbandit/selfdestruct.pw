<?php

class godaddyPass {
    // godaddy compliant character set
    public $letters   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    public $numbers   = '1234567890';
    public $specChars = '!@#%';

    public $pass = [];
    public $tmpPass = [];

    public function genpass() {
        // first char is a letter
        $this->tmpPass[0] = $this->letters[rand(0, strlen($this->letters)-1)];

        // random letters for rest of password
        for ($i = 1; $i < 14; $i++) {
            $randLet = $this->letters[rand(0, strlen($this->letters)-1)];
            $this->tmpPass[$i] = $randLet;
        }

        $this->pass = implode('', $this->tmpPass);

        // replace any letter but the first with a number
        $randRep1 = rand(1, strlen($this->pass)-1);
        $randNum = $this->numbers[rand(0, strlen($this->numbers)-1)];
        $this->pass[$randRep1] = $randNum;

        // replace any letter but the first or the number with a special character
        $randSpec = $this->specChars[rand(0, strlen($this->specChars)-1)];
        $randRep2 = rand(1, strlen($this->pass)-1);
        while ($randRep2 == $randRep1) {
            $randRep2 = rand(1, strlen($this->pass)-1);
        }
        $this->pass[$randRep2] = $randSpec;

        return $this->pass;
    }
}

?>
