<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчет о реализации автомобилей</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container">
    <h1>Отчет о реализации автомобилей</h1>

    <form method="GET" action="">
        <label for="month">Выберите месяц:</label>
        <input type="month" name="month" id="month" required>
        <button type="submit" class="btn btn-primary">Сформировать отчет</button>
    </form>

    <?php
    $host = 'localhost';
    $dbname = 'laba1';
    $user = 'postgres';
    $password = '584252302';

    $dsn = "pgsql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);

    if (isset($_GET['month'])) {
        $selected_month = $_GET['month'];

        $sql = "SELECT car_models.model_name, price_list.car_price, price_list.preparation_cost, price_list.transportation_cost,
                        (price_list.car_price + price_list.preparation_cost + price_list.transportation_cost) AS total_cost,
                        suppliers.supplier_name
                FROM sales
                JOIN car_models ON sales.model_id = car_models.model_id
                JOIN price_list ON car_models.model_id = price_list.model_id
                JOIN suppliers ON car_models.supplier_id = suppliers.supplier_id
                WHERE TO_CHAR(sales.sale_date, 'YYYY-MM') = :selected_month
                ORDER BY car_models.model_name";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['selected_month' => $selected_month]);

        if ($stmt->rowCount() > 0) {
            echo "<h2>Отчет о реализации автомобилей за " . date('F Y', strtotime($selected_month)) . "</h2>";
            echo "<table class='table table-bordered'>
                    <tr>
                        <th>Модель</th>
                        <th>Цена (руб.)</th>
                        <th>Предпродажная подготовка (руб.)</th>
                        <th>Транспортные издержки (руб.)</th>
                        <th>Итоговая стоимость (руб.)</th>
                        <th>Поставщик</th>
                    </tr>";

            $current_model = '';
            $model_total_cost = 0;
            $model_count = 0;
            $all_total_cost = 0; // Для подсчета общего итога

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Если модель изменилась, выводим итог по предыдущей
                if ($current_model !== '' && $current_model !== $row['model_name']) {
                    echo "<tr class='font-weight-bold'>
                            <td colspan='4' class='text-right'>Итого по модели: </td>
                            <td colspan='2'>$model_total_cost руб. ($model_count продаж)</td>
                          </tr>";
                    $model_total_cost = 0; // Сбрасываем итоги
                    $model_count = 0;
                }

                // Вывод строки
                echo "<tr>
                        <td>{$row['model_name']}</td>
                        <td>{$row['car_price']} руб.</td>
                        <td>{$row['preparation_cost']} руб.</td>
                        <td>{$row['transportation_cost']} руб.</td>
                        <td>{$row['total_cost']} руб.</td>
                        <td>{$row['supplier_name']}</td>
                      </tr>";

                // Суммируем итоги по текущей модели
                $current_model = $row['model_name'];
                $model_total_cost += $row['total_cost'];
                $model_count++;

                // Добавляем к общему итогу
                $all_total_cost += $row['total_cost'];
            }

            // Итог по последней модели
            if ($model_count > 0) {
                echo "<tr class='font-weight-bold'>
                        <td colspan='4' class='text-right'>Итого по модели: </td>
                        <td colspan='2'>$model_total_cost руб. ($model_count продаж)</td>
                      </tr>";
            }

            echo "</table>";

            // Вывод общего итога
            echo "<p class='font-weight-bold'>Общий итог: $all_total_cost руб.</p>";
        } else {
            echo "<p>Нет данных за выбранный месяц.</p>";
        }
    }
    ?>
</body>

</html>