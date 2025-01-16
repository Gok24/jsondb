<?php
// Read the contents of the JSON file
$jsonData = file_get_contents('data.json');

// Decode the JSON data into a PHP array
$data = json_decode($jsonData, true);

// Get the new processor details from the POST request
$manufacturer = $_POST['manufacturer'];
$series = $_POST['series'];
$ig = $_POST['ig'];
$cache = $_POST['cache'];
$core = $_POST['core'];
$thread = $_POST['thread'];
$freq = $_POST['freq'];
$img = $_POST['img'];

// Generate a new ID based on the last one
$newId = count($data['processors']) + 1;

// Add the new processor to the array
$data['processors'][] = [
    'id' => $newId,
    'manufacturer' => $manufacturer,
    'series' => $series,
    'ig' => $ig,
    'cache' => $cache,
    'core' => $core,
    'thread' => $thread,
    'freq' => $freq,
    'img' => $img
];

// Save the updated data back to the JSON file
file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));

// Send back the response with the new ID
echo json_encode([
    'message' => 'Processor added successfully!',
    'newId' => $newId
]);
?>
