<?php
// Database connection file
$host = "localhost";
$user = "root";
$password = "";
$database = "travel_website";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Direct photograph URLs. Using normal <img> URLs instead of remote images
// embedded inside SVG prevents the blank-image problem seen in some browsers.
function destination_image_url(string $image): string {
    $map = [
        'assets/india.svg' => 'https://thumb.wikimedia.org/wikipedia/commons/thumb/c/c5/Meenakshi_Amman_Temple%2C_Madurai.jpg/960px-Meenakshi_Amman_Temple%2C_Madurai.jpg',
        'assets/paris.svg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Eiffel_Tower_Paris_Aug_2026.jpg/960px-Eiffel_Tower_Paris_Aug_2026.jpg',
        'assets/bali.svg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Rice_Terrace_View%2C_Bali%2C_Indonesia.jpg/960px-Rice_Terrace_View%2C_Bali%2C_Indonesia.jpg',
        'assets/dubai.svg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/16/Burj_Khalifa_Image.jpg/960px-Burj_Khalifa_Image.jpg',
        'assets/singapore.svg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c2/Marina_Bay_Sands%2C_2026.jpg/960px-Marina_Bay_Sands%2C_2026.jpg',
        'assets/korea.svg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/20/Gyeongbokgung_Palace_%2855219197845%29.jpg/960px-Gyeongbokgung_Palace_%2855219197845%29.jpg'
    ];
    return $map[$image] ?? $image;
}
?>
