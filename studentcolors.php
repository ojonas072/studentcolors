<!-- By Owen Jonas -->
<?php
require __DIR__ . '/../vendor/autoload.php'; // Composer autoloader

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';
$dbname = $_ENV['DB_NAME'] ?? '';

$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "
  SELECT 
    s.name_given, 
    s.name_family, 
    m.major_long, 
    c.name AS color_name
  FROM students s
  LEFT JOIN school_majors m ON s.major_id = m.major_id
  LEFT JOIN colors c ON s.favorite_color = c.color_id
";

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student Colors</title>
  <style>
    body { font-family: Arial, sans-serif; }
    table { border-collapse: collapse; margin: 20px auto; width: 80%; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
    th { background-color: #f2f2f2; }
    td:last-child { font-weight: bold; }
  </style>
</head>
<body>
<h2 style="text-align:center;">Student Colors</h2>

<table>
  <tr>
  <th>Given Name</th>
  <th>Family Name</th>
  <th>Major</th>
  <th>Favorite Color</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['name_given']) ?></td>
      <td><?= htmlspecialchars($row['name_family']) ?></td>
      <td><?= htmlspecialchars($row['major_long']) ?></td>
      <td style="background-color: <?= htmlspecialchars($row['color_name']) ?>;">
      <td><?= htmlspecialchars($row['color_name']) ?></td>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

</body>
</html>

<?php $conn->close(); ?>