<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Kalkulator kredytowy</title>
    <link rel="stylesheet" type="text/css" href="{$smarty.const._APP_URL}/css/style.css">
</head>
<body>
    <h2>Kalkulator kredytowy</h2>
    <form action="app\calc.php" method="post">
        <label for="id_credit">Kwota kredytu:</label>
        <input id="id_credit" type="number" step="any" name="credit" min="1" required value="{$form->credit|default:''}" /><br />

        <label for="id_percent">Oprocentowanie:</label>
        <input id="id_percent" type="number" step="any" name="percent" min="0" required value="{$form->percent|default:''}" /><br />

        <label for="id_years">Liczba lat kredytu:</label>
        <input id="id_years" type="number" name="years" min="1" required value="{$form->years|default:''}" /><br />

        <input type="submit" value="Oblicz miesięczną ratę" />
    </form>

    {if $messages->messages}
        <div class="error-message">
            {foreach $messages->messages as $message}
                {$message}<br>
            {/foreach}
        </div>
    {/if}

    {if $result->monthlyPayment}
        <div class="result-container">
            Wartość miesięcznej raty: <strong>{$result->monthlyPayment} zł</strong>
        </div>
    {/if}
</body>
</html>
