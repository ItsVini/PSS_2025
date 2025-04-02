<?php
/* Smarty version 4.3.2, created on 2024-05-16 18:20:26
  from 'D:\tools\htdocs\credit_calc_4\templates\CalcView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_6646324a025a04_41180938',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '914b6f9682930382130defa3c16d59437177c601' => 
    array (
      0 => 'D:\\tools\\htdocs\\credit_calc_4\\templates\\CalcView.tpl',
      1 => 1715876420,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6646324a025a04_41180938 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Kalkulator kredytowy</title>
    <link rel="stylesheet" type="text/css" href="<?php echo (defined('_APP_URL') ? constant('_APP_URL') : null);?>
/css/style.css">
</head>
<body>
    <h2>Kalkulator kredytowy</h2>
    <form action="app\calc.php" method="post">
        <label for="id_credit">Kwota kredytu:</label>
        <input id="id_credit" type="number" step="any" name="credit" min="1" required value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['form']->value->credit ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
" /><br />

        <label for="id_percent">Oprocentowanie:</label>
        <input id="id_percent" type="number" step="any" name="percent" min="0" required value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['form']->value->percent ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
" /><br />

        <label for="id_years">Liczba lat kredytu:</label>
        <input id="id_years" type="number" name="years" min="1" required value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['form']->value->years ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
" /><br />

        <input type="submit" value="Oblicz miesięczną ratę" />
    </form>

    <?php if ($_smarty_tpl->tpl_vars['messages']->value->messages) {?>
        <div class="error-message">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['messages']->value->messages, 'message');
$_smarty_tpl->tpl_vars['message']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['message']->value) {
$_smarty_tpl->tpl_vars['message']->do_else = false;
?>
                <?php echo $_smarty_tpl->tpl_vars['message']->value;?>
<br>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </div>
    <?php }?>

    <?php if ($_smarty_tpl->tpl_vars['result']->value->monthlyPayment) {?>
        <div class="result-container">
            Wartość miesięcznej raty: <strong><?php echo $_smarty_tpl->tpl_vars['result']->value->monthlyPayment;?>
 zł</strong>
        </div>
    <?php }?>
</body>
</html>
<?php }
}
