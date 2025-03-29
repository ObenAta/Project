<?php
require_once 'includes/config.php';

if (isset($_GET['q'])) {
    $q = trim($_GET['q']);
    if ($q !== "") {
        $stmt = $pdo->query("SELECT name FROM products");
        $names = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $suggestion = "";
        $shortest = -1;
        foreach ($names as $name) {
            $lev = levenshtein(strtolower($q), strtolower($name));
            if ($lev == 0) {
                $suggestion = $name;
                $shortest = 0;
                break;
            }
            if ($lev <= $shortest || $shortest < 0) {
                $suggestion = $name;
                $shortest = $lev;
            }
        }
        echo json_encode(['suggestion' => $suggestion]);
        exit;
    }
}
echo json_encode([]);
?>
