<?php
require_once __DIR__ . '/settings.php';

mysqli_report(MYSQLI_REPORT_OFF);
$dbconn = mysqli_connect($host, $user, $pwd, $sql_db);
$error = '';
$cars = [];

if (!$dbconn) {
    $error = 'Unable to connect to the database.';
} else {
    mysqli_set_charset($dbconn, 'utf8mb4');
    $result = mysqli_query($dbconn, 'SELECT car_id, make, model, price, yom FROM cars ORDER BY car_id');
    if ($result === false) {
        $error = 'Unable to read car records.';
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $cars[] = $row;
        }
        mysqli_free_result($result);
    }
    mysqli_close($dbconn);
}

function display($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Used Cars</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        table { border-collapse: collapse; }
        th, td { border: 1px solid #888; padding: 0.5rem 0.8rem; text-align: left; }
    </style>
</head>
<body>
    <h1>Used Cars</h1>
    <?php if ($error !== ''): ?>
        <p><?= display($error) ?></p>
    <?php elseif (count($cars) === 0): ?>
        <p>There are no cars to display.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Car ID</th><th>Make</th><th>Model</th><th>Price</th><th>Year of Manufacture</th></tr></thead>
            <tbody>
            <?php foreach ($cars as $car): ?>
                <tr>
                    <td><?= display($car['car_id']) ?></td>
                    <td><?= display($car['make']) ?></td>
                    <td><?= display($car['model']) ?></td>
                    <td>$<?= number_format((float) $car['price'], 2) ?></td>
                    <td><?= display($car['yom']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
