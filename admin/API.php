<?php
// Secure API Endpoint to Share Anonymized Patient Data
include("connection.php");

// Authenticate API key
$apiKey = $_SERVER['HTTP_API_KEY'];
if ($apiKey !== "your-secure-api-key") {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

// Query anonymized data
$result = $database->query("SELECT * FROM anonymized_patient_data");
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($data);

function anonymizeData($patient) {
    return [
        'name' => substr(hash('sha256', $patient['pname']), 0, 10),
        'email' => 'anon' . rand(1000, 9999) . '@example.com',
        'dob' => date('Y', strtotime($patient['pdob'])) // Generalize DOB
    ];
}

?>
