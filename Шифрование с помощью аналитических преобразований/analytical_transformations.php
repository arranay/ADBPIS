<?php

    //умножение матрицы символов для шифрования на ключ
    function multiplication($key, $matrix){
        $result=[];
        for($i=0; $i<3; $i++){
            $sum=0;
            for($j=0; $j<3; $j++){
               $sum+=$key[$i][$j]*$matrix[$j];
            }
            array_push($result,$sum);
        }
        return $result;
    }

    //определитель матрицы два на два
    function det_matrix_2x2($matrix){
        return $matrix[0]*$matrix[3]-$matrix[1]*$matrix[2];
    }

    //определитель матрицы три на три
    function det_matrix_3x3($matrix){
        $det=$matrix[0][0]*det_matrix_2x2([$matrix[1][1], $matrix[1][2], 
                                            $matrix[2][1],$matrix[2][2]]);
        $det-=$matrix[0][1]*det_matrix_2x2([$matrix[1][0], $matrix[1][2], 
                                            $matrix[2][0],$matrix[2][2]]);
        $det+=$matrix[0][2]*det_matrix_2x2([$matrix[1][0], $matrix[1][1], 
                                            $matrix[2][0],$matrix[2][1]]);
        return $det;
    }

    //функция для нахождения обратной матрицы
    function inverse_matrix($key){
        $transposed_matrix=[];
        for($j=0; $j<3; $j++){
            $matrix=[];
            for($i=0; $i<3; $i++){
                array_push($matrix, $key[$i][$j]);
            }
            array_push($transposed_matrix, $matrix);
        }

       $det=det_matrix_3x3($key);
       $inverse_matrix=[];
       for($i=0; $i<3; $i++){
            $matrix=[];
            for($j=0; $j<3; $j++)
            {
                $matrix2=[];
                for($i1=0; $i1<3; $i1++)
                    for($j1=0; $j1<3; $j1++)
                        if(($i1!=$i)and($j1!=$j)){
                            array_push($matrix2, $transposed_matrix[$i1][$j1]);
                        }
                        $item=(det_matrix_2x2($matrix2)*((-1)**($i+$j)))/$det;
                        array_push($matrix, $item);
            }
            array_push($inverse_matrix, $matrix);
        }
        return $inverse_matrix;
    }

    $key = [[1, 7, 12],
            [8, -5, 0],
            [7, 8, 2]];
    
    $alphabet=[ 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и',
                'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т',
                'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 
                'э', 'ю', 'я', ' '];

    

    echo "\n выберите действия\n   1: зашифровать сообщение\n   2: расшифровать сообщение\n";
    $action=readline(" ");

    switch ($action){
        case 1: 

            //получаем сообщение, переводим все буквы в нижний регистр и удаляем знаки препинания
            $msg = readline("  введите текст сообщения: ");
            $msg=mb_strtolower($msg);
            $array_of_characters = array(".", ",", "-", ":", "«", "»");
            foreach($array_of_characters as $character) 
                $msg=str_replace($character, '',$msg);

            //разбиваем сообщение на массив символов
            $msg = preg_split("//u", $msg, -1, PREG_SPLIT_NO_EMPTY);

            //Заменим буквы алфавита цифрами, соответствующими их порядковому номеру в алфавите
            $index_number_msg=[];
            foreach($msg as $m){
                array_push($index_number_msg, array_search($m, $alphabet));
            }

            //кол-во элементов массива олжно быть равным 3, для последующего шифрования
            $first_count=count($index_number_msg);
            while(count($index_number_msg)%3!=0){
                array_push($index_number_msg, 99);
            }

            //разбиваем имеющийся массив на матрицы по три элемента
            $index_number_msg=array_chunk($index_number_msg, 3);

            //перемножаем матрицу-ключ и полученные из сообщения буквы
            $encrypted_message=[];
            foreach($index_number_msg as $item){
                $encrypted_message=array_merge($encrypted_message, multiplication($key, $item));
            }

            echo "\n\n  зашифрованное сообщение: ";
            for($i=0;$i<$first_count;$i++) echo $encrypted_message[$i]." ";
            break;

        case 2: 
            $msg = readline('введите текст сообщения: ');

            //получаем массив символов из введенной строки
            $msg = preg_split("/[\s,]+/",  $msg);

           ///кол-во элементов массива олжно быть равным 3, для последующего шифрования
            $first_count=count($msg);
            while(count($msg)%3!=0){
                array_push($msg, 99);
            }

            //делим элементы массива-сообщения на группы по три символа
            $index_number_msg=array_chunk($msg, 3);

            //получаем обратную матрицу для расшифровки
            $inverse_matrix=inverse_matrix($key);


            //перемножаем матрицу-ключ и полученные из сообщения буквы
            $encrypted_message=[];
            foreach($index_number_msg as $item){
                $matrix=[];
                foreach(multiplication($inverse_matrix, $item) as $m){
                    array_push($matrix, round($m));
                }
                $encrypted_message=array_merge($encrypted_message, $matrix);
            }

            //возвращаем буквы
            $msg=[];
            foreach($encrypted_message as $message){
               if($message!=99) array_push($msg, $alphabet[$message]);
            }
            
            echo "\n  расшифрованное сообщение: ";
            for($i=0; $i<count($msg) ;$i++) echo $msg[$i];
            break;

        default:
            echo "\n был введен не верный символ";
        }    
?>