<?php 

    function mod_add($binone, $bintwo){
        $binone = preg_split("//u", $binone, -1, PREG_SPLIT_NO_EMPTY);
        $bintwo = preg_split("//u", $bintwo, -1, PREG_SPLIT_NO_EMPTY);

        $result="";
        for($i=0; $i<count($binone); $i++){
            $result.=($binone[$i]+$bintwo[$i])%2;
        }

        return $result;
    }

    function decrypt_mod_add($binone, $bintwo){
        $binone = preg_split("//u", $binone, -1, PREG_SPLIT_NO_EMPTY);
        $bintwo = preg_split("//u", $bintwo, -1, PREG_SPLIT_NO_EMPTY);

        $result="";
        for($i=0; $i<count($binone); $i++){
            if($binone[$i]==$bintwo[$i]){
                $result.="0";
            } else $result.="1";   
        }

        return $result;
    }

   $alphabet = ["а"=>"000001", "б"=>"001001", "в"=>"001010",
                "г"=>"001011", "д"=>"001100", "е"=>"000010",
                "ж"=>"001101", "з"=>"001110", "и"=>"000011",
                "й"=>"011111", "к"=>"001111", "л"=>"010000", 
                "м"=>"010001", "н"=>"010010", "о"=>"000100", 
                "п"=>"010011", "р"=>"010100", "с"=>"010101", 
                "т"=>"010110", "у"=>"000101", "ф"=>"010111", 
                "х"=>"011000", "ц"=>"011001", "ч"=>"011010", 
                "ш"=>"011011", "щ"=>"011100", "ъ"=>"100000",
                "ы"=>"011101", "ь"=>"011110", "э"=>"000110", 
                "ю"=>"000111", "я"=>"001000", " "=>"100001"];

    //$gammy_signs = [2, 3, 10, 4, 1, 5, 6, 7, 8, 11, 15, 14, 12, 13, 9, 0];
    $gammy_signs = [1, 9, 2, 8, 3];
    $gammy_signs_binary = [];

    $msg=readline(" введите сообщение: ");

    //Двоичное представление знаков гаммы
    foreach ($gammy_signs as $sign){
        $s=decbin($sign);
        $len=strlen($s);
        for ($i=0; $i<6-$len; $i++){
            $s="0".$s;
        }
        array_push($gammy_signs_binary, $s);
    }
    echo "\nЗнаки гаммы: \n";
    for($i=0; $i<count($gammy_signs); $i++){
        if(strlen($gammy_signs[$i])==2)
            echo "  ".$gammy_signs[$i]." -> ".$gammy_signs_binary[$i]."\n";
        else echo "  ".$gammy_signs[$i]."  -> ".$gammy_signs_binary[$i]."\n";
    }

    echo "\n выберите действия\n   1: зашифровать сообщение\n   2: расшифровать сообщение\n";
    $action=readline();
    switch ($action){
        case 1: 
            echo "\n зашифровываем сообщение\n\n";

            //удаляем пробелы и знаки препинания из сообщения
            $msg=mb_strtolower($msg);
            $array_of_characters = array(".", ",", "-", ":");
            foreach($array_of_characters as $character) 
                $msg=str_replace($character, '',$msg);

            //делаем массив из строки
            $msg = preg_split("//u", $msg, -1, PREG_SPLIT_NO_EMPTY);

            //каждой букве сообщения присваиваем двоичный код из алфавита
            $msg_binary=[];
            foreach($msg as $ms){
                array_push($msg_binary, $alphabet[$ms]);
            }

            $j=1;
            for($i=0; $i<count($msg); $i++){
                echo "  ".$msg[$i]." -> ".$msg_binary[$i]."  ";
                if (($j++)%7==0) echo "\n";
            }

            //сложение по модулю 2
            $j=0; $result="";
            for($i=0; $i<count($msg_binary); $i++){
                if ($j<count($gammy_signs_binary)){
                    $result.=mod_add($msg_binary[$i], $gammy_signs_binary[$j])." ";
                    $j++;
                }
                else{
                    $result.=mod_add($msg_binary[$i], $gammy_signs_binary[0])." ";
                    $j=1;
                }
            }

            echo "\n\n зашифрованное сообщение: \n  ".$result."\n";

            break;
        case 2: 
            echo "\n расшифровываем сообщение\n\n";

            //делаем массив из строки
            $msg=str_replace(' ', '', $msg);
            $msg=str_split($msg, 6);
            
            //получаем массив букв в двоичном виде
            $j=0; $result=[];
            for($i=0; $i<count($msg); $i++){
                if ($j<count($gammy_signs_binary)){
                    array_push($result, decrypt_mod_add($msg[$i], $gammy_signs_binary[$j]));
                    $j++;
                }
                else{
                    array_push($result, decrypt_mod_add($msg[$i], $gammy_signs_binary[0]));
                    $j=1;
                }
            }

            echo " массив букв в двоичном виде: \n";
            $j=1;
            for($i=0; $i<count($result); $i++){
                echo "  ".$result[$i]."  ";
                if (($j++)%7==0) echo "\n";
            }

            //возвращаем буквы
            $finally_line="";
            foreach($result as $r){
                $finally_line.=array_search($r, $alphabet);
            }

            echo "\n\n расшифрованное сообщение: \n  ".$finally_line."\n";

            break;
        default:
            echo "\n был введен не верный символ";
    }

?>