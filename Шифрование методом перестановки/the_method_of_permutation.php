<?php

// удаляем все пробелы и символы и переводим в нижний регистр
function conversion($line){
    $line=mb_strtolower($line);
    $array_of_characters = array(' ', ".", ",", "-", ":");
    foreach($array_of_characters as $character) 
        $line=str_replace($character, '',$line);
    return $line;
}

// каждой букве ключа присваиваем номер
function selecting_a_number($key, $alphabet){
    $key = preg_split("//u", $key, -1, PREG_SPLIT_NO_EMPTY);
    $alphabet = preg_split("//u", $alphabet, -1, PREG_SPLIT_NO_EMPTY);

    $array_key=array();
    array_push($array_key,$key);
    $array_number=array_fill(0, count($key), 0);
    $i=0;
    foreach($alphabet as $a)
        foreach($key as $c=>$k)
            if($k==$a) {
                $array_number[$c]=++$i;
            }
    array_push($array_key,$array_number);
    return  $array_key;
}

// в массив с ключем добавляется текст для шифрования
function add_line($line, $array_key){
    $array_for_encryption=array();
    $array_for_encryption=array_merge($array_for_encryption, $array_key);
    $line = preg_split("//u", $line, -1, PREG_SPLIT_NO_EMPTY);
    $array_symbols=array();
    $i=0;
    foreach($line as $l){
        if ($i<count($array_key[0])){
            $array_symbols[$i] = $l;
            $i++;
        }else{
            array_push($array_for_encryption, $array_symbols);
            $array_symbols=array();
            $array_symbols[0]=$l;
            $i=1;
        }
    }
    $c=count($array_symbols);
    for ($i=$c; $i<=count($array_key[0]); $i++){
        $array_symbols[$i]='';
    }
    array_push($array_for_encryption, $array_symbols);
    return $array_for_encryption;
}

// из массива записываем в строку столбики в соответствии с номерами букв ключа
function returning_an_encrypted_line($array_for_encryption){
    $line="";
    for ($i = 1; $i <= count($array_for_encryption[0]); $i++){
        $key = array_search($i, $array_for_encryption[1]);
        for($n=2; $n<count($array_for_encryption);$n++){
        $line=$line.$array_for_encryption[$n][$key];
        } 
    $line=$line." "; 
    }
    return $line;
}

// возвращаем из зашифрованной строки массив символов
function return_an_array_of_symbol($line){
    $line = explode(" ", $line);
    $array_of_symbol=array();
    $max=0;
    foreach($line as $l){
        $l=preg_split("//u", $l, -1, PREG_SPLIT_NO_EMPTY);
        array_push($array_of_symbol, $l);
        if($max<count($l)) $max=count($l);
    }
    foreach($array_of_symbol as $key=>$symbol){
        if (count($symbol)<$max) $array_of_symbol[$key][$max-1]='';   
    }
    return $array_of_symbol;
}

// в массив с ключем добавляем текст для расшифровки
function return_array_for_decode($array_of_symbol, $array_key){
    for($j=0; $j<count($array_of_symbol[0]); $j++){
        $i=0;
        $array_for_decode=array();
        foreach($array_key[1] as $numb){
            $array_for_decode[$i]=$array_of_symbol[$numb-1][$j];            
            $i++;
        }
        array_push($array_key, $array_for_decode); 
    } 
    return $array_key; 
}

//*****************************ИСХОДНЫЕ ДАННЫЕ***********************************/
$alphabet='абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
$line=readline("Введите исходный текст: ");
$key=readline("Введите ключ: ");
echo "\n";

$action=readline("1-зашифровать, 2-расшифровать:");
echo "\n";

switch ($action){
//**********************************ШИФРОВАНИЯ***********************************/
    case (string)1:

        //переводим строку в нижний регистр, удаляем пробелы и знаки препинания
        $line=conversion($line);
        echo "Преобразуем строку: \n$line\n\n";

        //каждой букве ключа задаём номер
        echo "  Каждой букве ключа присваиваем номер: \n";
        $array_key=selecting_a_number($key, $alphabet);
        foreach ($array_key as $k=>$a){
            foreach ($a as $i){
                if((gettype($i)=="integer")&(strlen("$i")==2)) echo " $i ";
                else echo "  $i ";
            }
            echo "\n";
        }
        echo "\n\n";

        //добавляем в массив текст
        $array_for_encryption=add_line($line, $array_key);
        echo " Записываем весь текст в массив: \n";
        foreach ($array_for_encryption as $k=>$a){
            foreach ($a as $i){
                if((gettype($i)=="integer")&(strlen("$i")==2)) echo " $i ";
                else echo "  $i ";
            }
            echo "\n";
        }
        echo "\n\n";

        //возвращаем зашифрованный текст обратно в строку
        echo "Зашифрованное сообщение: \n";
        $line=returning_an_encrypted_line($array_for_encryption);
        echo "$line\n\n";
        break;



//**********************************РАСШИФРОВКА***********************************/
    case (string)2:

        //Вовзвращаем массив символов из строки
        $array_of_symbol=return_an_array_of_symbol($line);
        // foreach($array_of_symbol as $arr){
        //     foreach($arr as $a){
        //         echo $a;
        //     }
        //     echo "\n";
        // }
        // echo "\n\n";

        //каждой букве ключа задаём номер
        echo "  Каждой букве ключа присваиваем номер: \n";
        $array_key=selecting_a_number($key, $alphabet);
        foreach ($array_key as $k=>$a){
            foreach ($a as $i){
                if((gettype($i)=="integer")&(strlen("$i")==2)) echo " $i ";
                else echo "  $i ";
            }
            echo "\n";
        }
        echo "\n\n";

        //собираем масив для дешифрования
         $array_for_decode=return_array_for_decode($array_of_symbol, $array_key);
         echo " Массив для дешифрования: \n";
         foreach ($array_for_decode as $k=>$a){
            foreach ($a as $i){
                if((gettype($i)=="integer")&(strlen("$i")==2)) echo " $i ";
                else echo "  $i ";
            }
            echo "\n";
        }
        echo "\n\n";

        break;

    default: echo "Был введен неверный символ(";
}
?>