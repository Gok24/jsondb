<?php
// Read the contents of the JSON file
$jsonData = file_get_contents('data.json');

// Decode the JSON data into a PHP array
$data = json_decode($jsonData, true);

// Get the form data
$processorId = isset($_POST['id']) ? (int)$_POST['id'] : null;
$manufacturer = isset($_POST['manufacturer']) ? $_POST['manufacturer'] : '';
$series = isset($_POST['series']) ? $_POST['series'] : '';
$ig = isset($_POST['ig']) ? $_POST['ig'] : '';
$cache = isset($_POST['cache']) ? $_POST['cache'] : '';
$core = isset($_POST['core']) ? $_POST['core'] : '';
$thread = isset($_POST['thread']) ? $_POST['thread'] : '';
$img = isset($_POST['img']) ? $_POST['img'] : '';

// Find the user by ID and update the information
$userUpdated = false;
foreach ($data['processors'] as &$processors) {
    if ($processors['id'] == $processorId) {
        $processors['manufacturer'] = $manufacturer;
        $processors['series'] = $series;
        $processors['ig'] = $ig;
        $processors['cache'] = $cache;
        $processors['core'] = $core;
        $processors['thread'] = $thread;
        $processors['img'] = $img;
        $userUpdated = true;
        break;
    }
}

// If the user was updated, save the updated data to the JSON file
if ($userUpdated) {
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('data.json', $jsonData);
    echo json_encode(['message' => 'updated successfully!']);
} else {
    echo json_encode(['message' => 'not found']);
}
?>
