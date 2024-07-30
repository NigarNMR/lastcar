<?php

require "functions.php";

//Скрипт расчета цепочек
set_time_limit(0);
// Запускаем цикл работы скрипта
while (TRUE) {
    $ar_result = [];
    if (!($db= pg_connect("host=locale_get_default port=5432 dbname=general_crosses user=postgres password=admin"))){
        // Если не было установленно соединение, то скрипт засыпает
        sleep(1);}
        else {
        // Иначе продолжаем выполнение
        // Получаем новый необработанный кросс
        $task_row = pg_query($db,"SELECT * FROM test WHERE status=0 limit 1");

        // Проверка на получение данных
            $countRows = pg_num_rows($task_row);
            if ($countRows == 0) {
            // Данных нет, скрипт засыпает
            pg_close($db);
            sleep(10);
        } else {

                while ($task_data = pg_fetch_assoc($task_row)) {
                    $br1 = $task_data['brand_id1'];
                    $br2 = $task_data['brand_id2'];
                    $ak1 = $task_data['akey1'];
                    $ak2 = $task_data['akey2'];


                    // Обновление статуса задачи в БД (В обработке)
                    $sq = "UPDATE test SET status=1 WHERE brand_id1='$br1'  AND akey1='$ak1' AND brand_id2='$br2' AND akey2='$ak2'";
                    pg_query($db, $sq);


                    // Получение замен для первой пары (BRAND_ID1 и AKEY1)
                    $sql = "(SELECT brand_id2 AS brand_id, akey2 AS akey FROM tdm_links WHERE brand_id1='$br1' AND akey1='$ak1') UNION ALL (SELECT brand_id1 AS brand_id, akey1 AS akey FROM tdm_links WHERE brand_id2='$br1'AND akey2='$ak1')";

                    $data_pair_1 = pg_query($db, $sql);
                    // Проверяем на наличие данных
                    while ($data_row = pg_fetch_assoc($data_pair_1)) {
                        // Проверяем корректность данных замены
                        if (!empty($data_row['brand_id']) && !empty($data_row['akey'])) {
                            // Проверяем на совпадение с данными второй пары
                            if ((int)$data_row['brand_id'] === (int)$task_data['brand_id2']
                                && (int)$data_row['akey'] === (int)$task_data['akey2']
                            ) {
                                // Если совпадение обнаружено, то пропускаем итерацию
                                continue;
                            }
                            // Занесение кросса
                            $ar_result[] = [
                                'brand_id1' => $task_data['brand_id2'],
                                'brand_id2' => $data_row['brand_id'],
                                'akey1' => $task_data['akey2'],
                                'akey2' => $data_row['akey'],
                            ];


                        }
                    }

                    // Получение замен для второй пары (BRAND_ID2 и AKEY2)
                    $sql = " (SELECT brand_id2 AS brand_id, akey2 AS akey FROM tdm_links WHERE brand_id1='$br2' AND akey1='$ak2') UNION ALL (SELECT brand_id1 AS brand_id, akey1 AS akey FROM tdm_links WHERE brand_id2='$br2' AND akey2='$ak2')";

                    $data_pair_2 = pg_query($db, $sql);

                    // Проверяем на наличие данных
                    while ($data_row = pg_fetch_assoc($data_pair_2)) {
                        // Проверяем корректность данных замены
                        if (!empty($data_row['brand_id']) && !empty($data_row['akey'])) {
                            // Проверяем на совпадение с данными первой пары
                            if ((int)$data_row['brand_id'] === (int)$task_data['brand_id1']
                                && (int)$data_row['akey'] === (int)$task_data['akey1']
                            ) {
                                // Если совпадение обнаружено, то пропускаем итерацию
                                continue;
                            }
                            // Занесение кросса
                            $ar_result[] = [
                                'brand_id1' => $task_data['brand_id1'],
                                'brand_id2' => $data_row['brand_id'],
                                'akey1' => $task_data['akey1'],
                                'akey2' => $data_row['akey'],
                            ];

                        }
                    }
                    // Дополнение массива кроссов исходными данными задачи
                    $ar_result[] = [
                        'brand_id1' => $task_data['brand_id1'],
                        'brand_id2' => $task_data['brand_id2'],
                        'akey1' => $task_data['akey1'],
                        'akey2' => $task_data['akey2'],

                    ];

                }


           // pg_query("BEGIN") or die("Could not start transaction\n");
            $counter = 0;
            $total_count = count($ar_result);
            $ar_sql_pieces = [];
            // Предустановка статуса выполненных операций вставки
            $insert_status = 1;
            foreach ($ar_result as $ar_data) {
                $counter++;
                if ($counter % 100 === 1) {
                    $sql_statement = "INSERT INTO tdm_links(brand_id1,brand_id2,akey1,akey2,side,code) VALUES ";
                }

                $arBr1=$ar_data['brand_id1'];
                $arBr2=$ar_data['brand_id2'];
                $arA1=Akey($ar_data['akey1']);
                $arA2=Akey($ar_data['akey2']);


                $ar_sql_pieces[] = "('$arBr1', '$arBr2','$arA1','$arA2','0', 'TecDoc')";

                if (($counter % 10 === 0 || $counter === $total_count) && !empty($ar_sql_pieces)) {
                    $sql_statement .= implode(", ", $ar_sql_pieces) . ";";
                   $crosses_result = pg_query($db,$sql_statement);


                    if (!$crosses_result) {
                        $insert_status = 0;

                    }
                    // Очистка массива и строки запроса
                    $ar_sql_pieces = [];
                    $sql_statement = '';
                }
            }

               $q = "UPDATE test SET status=2 WHERE brand_id1='$br1'  AND akey1='$ak1' AND brand_id2='$br2' AND akey2='$ak2'";
               $update_result = pg_query($db,$q);
            // Проверка успешного завершения всех выполненных запросов
            if ($insert_status && $update_result ) {
                // Если все выполнено успешно, подтвердить транзакцию
                echo "Commiting transaction\n";
               // pg_query("COMMIT") or die("Transaction commit failed\n");
            } else {
                echo "Rolling back transaction\n";
              //  pg_query("ROLLBACK") or die("Transaction rollback failed\n");;
            }
            // Закрытие соединения
           // pg_close($db);
           // sleep(0.33);
        }
        }
}


