<?php
// Read the contents of the JSON file
$jsonData = file_get_contents('data.json');

// Decode the JSON data into a PHP array
$data = json_decode($jsonData, true);

// Get the user ID from the query parameter
$userId = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Find the user by ID
$user = null;
foreach ($data['processors'] as $u) {
    if ($u['id'] == $userId) {
        $user = $u;
        break;
    }
}

// Return the user data as JSON
if ($user) {
    echo json_encode($user);
} else {
    echo json_encode(['message' => 'not found']);
}
?>
