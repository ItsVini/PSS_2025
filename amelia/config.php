<?php
$conf->debug = true; # set true during development and use in your code (for instance check if true to send additional message)

# ---- Webapp location
$conf->server_name = 'localhost';   # server address and port
$conf->protocol = 'http';           # http or https
$conf->app_root = '/amelia/public';   # project subfolder in domain (relative to main domain)

# ---- Database config - values required by Medoo
$conf->db_type     = 'mysql';
// nazwa hosta
$conf->db_server   = 'localhost';
// nazwa bazy (ta, którą utworzyłeś w phpMyAdmin)
$conf->db_name     = 'projekt_zadania';
// nazwa użytkownika MySQL (domyślnie root)
$conf->db_user     = 'root';
// hasło użytkownika (domyślnie puste w XAMPP)
$conf->db_pass     = '';
// zestaw znaków
$conf->db_charset  = 'utf8mb4';
// prefiks tabel (jeśli używasz)
$conf->db_prefix   = '';

# ---- Database config - optional values
$conf->db_port = '3306';
#$conf->db_prefix = '';
$conf->db_option = [ PDO::ATTR_CASE => PDO::CASE_NATURAL, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ];