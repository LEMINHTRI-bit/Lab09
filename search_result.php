<?php
require_once __DIR__ . '/settings.php';

mysqli_report(MYSQLI_REPORT_OFF);
$model = trim($_GET['model'] ?? '');
$cars = [];
$error = '';

if ($model !== '') {
    $conn = mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) {
        $error = 'Unable to connect to the database.';
    } else {
        mysqli_set_charset($conn, 'utf8mb4');
        $stmt = mysqli_prepare($conn, 'SELECT car_id, make, model, price, yom FROM cars WHERE model LIKE ? ORDER BY car_id');
        if (!$stmt) {
            $error = 'Unable to search for cars.';
        } else {
            $search = '%' . $model . '%';
            mysqli_stmt_bind_param($stmt, 's', $search);
            if (!mysqli_stmt_execute($stmt)) {
                $error = 'Unable to search for cars.';
            } else {
                $result = mysqli_stmt_get_result($stmt);
                if ($result === false) {
                    $error = 'Unable to read search results.';
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $cars[] = $row;
                    }
                    mysqli_free_result($result);
                }
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
    }
}

function safe($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
</head>
<body>
    <h1>Search results</h1>
    <?php if ($model === ''): ?>
        <p>Please enter a model to search.</p>
    <?php elseif ($error !== ''): ?>
        <p><?= safe($error) ?></p>
    <?php elseif (!$cars): ?>
        <p>No matching cars found.</p>
    <?php else: ?>
        <table border="1" cellpadding="5">
            <tr><th>ID</th><th>Make</th><th>Model</th><th>Price</th><th>Year</th></tr>
            <?php foreach ($cars as $car): ?>
                <tr>
                    <td><?= safe($car['car_id']) ?></td>
                    <td><?= safe($car['make']) ?></td>
                    <td><?= safe($car['model']) ?></td>
                    <td><?= safe($car['price']) ?></td>
                    <td><?= safe($car['yom']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <p><a href="search_form.php">Search again</a></p>
</body>
</html>
