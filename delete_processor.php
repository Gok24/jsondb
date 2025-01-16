<?php
// Read the contents of the JSON file
$jsonData = file_get_contents('data.json');

// Decode the JSON data into a PHP array
$data = json_decode($jsonData, true);

// Get the user ID from the POST request
$userId = isset($_POST['id']) ? (int)$_POST['id'] : null;

// Find the user by ID and remove it from the array
$userFound = false;
foreach ($data['processors'] as $index => $user) {
    if ($user['id'] == $userId) {
        unset($data['processors'][$index]);
        $userFound = true;
        break;
    }
}

// If the user was found and deleted, save the updated JSON
if ($userFound) {
    // Re-index the array to prevent index gaps
    $data['processors'] = array_values($data['processors']);

    // Encode the updated data back to JSON
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);

    // Write the updated data to the JSON file
    file_put_contents('data.json', $jsonData);

    echo json_encode(['message' => 'deleted successfully!']);
} else {
    echo json_encode(['message' => 'not found']);
}
?>
