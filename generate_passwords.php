<?php
// Password Hash Generator
// Use this to generate bcrypt hashes for default users

$passwords = [
    'admin123' => '',
    'staff123' => '',
    'password123' => ''
];

echo "<h2>Password Hash Generator</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Plain Password</th><th>Bcrypt Hash</th></tr>";

foreach ($passwords as $plain => $hash) {
    $bcrypt = password_hash($plain, PASSWORD_BCRYPT);
    echo "<tr>";
    echo "<td><strong>{$plain}</strong></td>";
    echo "<td><code>{$bcrypt}</code></td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr>";
echo "<h3>For SQL Insert:</h3>";
echo "<pre>";
echo "-- Admin User (admin@admin.com / admin123)\n";
echo "INSERT INTO users (name, user_type, email, password, phone) VALUES\n";
echo "('Admin User', 'admin', 'admin@admin.com', '" . password_hash('admin123', PASSWORD_BCRYPT) . "', '1234567890');\n\n";

echo "-- Staff User (staff@staff.com / staff123)\n";
echo "INSERT INTO users (name, user_type, email, password, phone) VALUES\n";
echo "('Staff User', 'staff', 'staff@staff.com', '" . password_hash('staff123', PASSWORD_BCRYPT) . "', '0987654321');\n";
echo "</pre>";
?>
