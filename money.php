<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <style>
    body {
        padding: 0 20px;
    }
       fieldset {
        width: 700px;
        padding: 20px; 
        margin: 10px 0;        
       }
       .head_test {
        font-size: 18px;
        margin: 0;
        margin-bottom: 15px;
       }
       input {
        margin-right: 20px;
       }
       h4 {
        margin: 15px 0;
       }
       p {
        margin: 0;
       }
       span {
        font-size: 24px;
       }
    </style>
    <title>Учет расходов</title>
</head>
<body>
<h2>Учет расходов</h2>
<form method="post">
    <fieldset>
        <p class="head_test">Введите данные о покупке</p>

            <input type="date" name="date-add">
            <input type="text" name="money" placeholder="сумма в формате 250.30">
            <input type="text" name="descript" placeholder="описание покупки">
            <input type="submit" name="add" value="Внести данные">
    </fieldset>
</form>

<?php
if (isset($_POST['add'])) {
    if (empty($_POST['date-add']) || empty($_POST['money']) || empty($_POST['descript'])) {
        echo "Внесите данные в форму";
    } else {
        $money    = $_POST['money'];
        $descript = $_POST['descript'];
        
        function clean($value)
        {
            $value = trim($value);
            $value = stripslashes($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value);
            
            return $value;
        }
        
        $money    = clean($money);
        $descript = clean($descript);
        
        
        $result[] = $_POST['date-add'];
        $result[] = $money;
        $result[] = $descript;
        $report   = implode(' - ', $result);
?>
<h4>Внесены следующие данные:</h4>
<p><?= $report ?></p>
<?php
        $fo  = fopen('money.csv', "ab");
        $fpc = fputcsv($fo, $result, ",");
        fclose($fo);
    }
}
?>
<form method="post">
    <fieldset>
    <p class="head_test">Расход за выбранную дату</p>

        <input type="date" name="date-check">
        <input type="submit" name="check" value="Рассчитать">
    </fieldset>
</form>
<?php
if (isset($_POST['check'])) {
    if (empty($_POST['date-check'])) {
        echo "Введите дату";
    } else {
        
        $fp = fopen('money.csv', "r");
        while ($data = fgetcsv($fp, 0, ",")) {
            $list[] = $data;
        }
        fclose($fp);
        
        $d = $_POST['date-check'];
?>
        <p><h4><?= $d ?> вы приобрели:</h4></p>
         <?php
        foreach ($list as $value) {
            if ($value[0] == $_POST['date-check']) {
                $check[]       = $value;
                $a             = "$value[1] - $value[2]";
                $money_check[] = $value[1];
?>
      <p><?= $a ?></p> 
        <?php
                $sum = array_sum($money_check);
                
            }
            
        }
        if (isset($sum)) {
?>
       <p><h4>Общая сумма покупок: <span><?= $sum ?></span> рублей</h4></p>
        <?php
        } else {
?>
       <p><h4>Покупок не было</h4></p>
        <?php
            
        }
    }
    
}
?>
</body>
</html>