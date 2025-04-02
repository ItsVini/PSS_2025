<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Kalkulator kredytowy</title>
    <link rel="stylesheet" type="text/css" href="{$smarty.const._APP_URL}/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .container {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="number"] {
            width: calc(100% - 10px);
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
        .result-container {
            margin-top: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Kalkulator kredytowy</h2>
        <form action="{$smarty.const._APP_URL}/app/calc.php" method="post">
            <div class="form-group">
                <label for="id_credit">Kwota kredytu:</label>
                <input id="id_credit" type="number" step="any" name="credit" min="1" required value="{if isset($credit)}{$credit}{/if}" />
            </div>

            <div class="form-group">
                <label for="id_percent">Oprocentowanie:</label>
                <input id="id_percent" type="number" step="any" name="percent" min="0" required value="{if isset($percent)}{$percent}{/if}" />
            </div>

            <div class="form-group">
                <label for="id_years">Liczba lat kredytu:</label>
                <input id="id_years" type="number" name="years" min="1" required value="{if isset($years)}{$years}{/if}" />
            </div>

            <input type="submit" value="Oblicz miesięczną ratę" />

            {if isset($messages)}
                <div class="error-message">
                    {foreach $messages as $msg}
                        {$msg}<br>
                    {/foreach}
                </div>
            {/if}

            {if isset($result)}
                <div class="result-container">
                    Wartość miesięcznej raty: <strong>{$result} zł</strong>
                </div>
            {/if}
        </form>
    </div>
</body>
</html>
