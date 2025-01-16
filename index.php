<?php
// Read the contents of the JSON file
$jsonData = file_get_contents('data.json');

// Decode the JSON data into a PHP array
$data = json_decode($jsonData, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Data in Table and Add/Update/Delete Processor</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <!-- Include jQuery -->
     
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        #response {
            margin-top: 20px;
            font-weight: bold;
        }
        .adder,.updater{
            display: none;
        }
        .active{
            display: block;
        }
    </style>
    <link rel="stylesheet" href="app.css">
</head>

<body>

<h1>Processors Database</h1>

<!-- Table displaying the processors -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Manufacturer</th>
            <th>Series</th>
            <th>IG</th>
            <th>Cache</th>
            <th>Core</th>
            <th>Thread</th>
            <th>Freq</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="processorTable">
        <?php
        // Loop through the 'processors' array and display each processor's data
        foreach ($data['processors'] as $processor) {
            echo '<tr id="processor-' . $processor['id'] . '">';
            echo '<td>' . htmlspecialchars($processor['id']) . '</td>';
            echo '<td>' . htmlspecialchars($processor['manufacturer']) . '</td>';
            echo '<td>' . htmlspecialchars($processor['series']) . '</td>';
            echo '<td>' . htmlspecialchars($processor['ig']) . '</td>';
            echo '<td>' . htmlspecialchars($processor['cache']) . '</td>';
            echo '<td>' . htmlspecialchars($processor['core']) . '</td>';
            echo '<td>' . htmlspecialchars($processor['thread']) . '</td>';
            echo '<td>' . htmlspecialchars($processor['freq']) . '</td>';
            echo '<td><img src="images/' . htmlspecialchars($processor['img']) . '" alt="' . htmlspecialchars($processor['series']) . '" width="50"></td>';
            echo '<td><button class="editProcessor" data-id="' . $processor['id'] . '">Edit</button> <button class="deleteProcessor" data-id="' . $processor['id'] . '">Delete</button></td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>
<button class="addfunc" id="addfunc">+ Add new</button>
<!-- Form to add a new processor -->
 <div class="adder ">
<h2>Add New Processor</h2>
<form id="addProcessorForm">
    <label for="manufacturer">Manufacturer:</label>
    <input type="text" id="manufacturer" name="manufacturer" required>

    <label for="series">Series:</label>
    <input type="text" id="series" name="series" required>

    <label for="ig">IG:</label>
    <input type="text" id="ig" name="ig" required>

    <label for="cache">Cache:</label>
    <input type="text" id="cache" name="cache" required>

    <label for="core">Core:</label>
    <input type="number" id="core" name="core" required>

    <label for="thread">Thread:</label>
    <input type="number" id="thread" name="thread" required>

    <label for="freq">Frequency:</label>
    <input type="text" id="freq" name="freq" required>

    <label for="img">Image File (name only):</label>
    <input type="text" id="img" name="img" required>

    <button type="submit">Add Processor</button>
</form>
</div>

<div class="updater">


<h2>Update Processor</h2>
<form id="updateProcessorForm">
    <label for="updateProcessorId">Processor ID:</label>
    <input type="number" id="updateProcessorId" name="id" readonly required>

    <label for="updateManufacturer">Manufacturer:</label>
    <input type="text" id="updateManufacturer" name="manufacturer" required>

    <label for="updateSeries">Series:</label>
    <input type="text" id="updateSeries" name="series" required>

    <label for="updateIg">IG:</label>
    <input type="text" id="updateIg" name="ig" required>

    <label for="updateCache">Cache:</label>
    <input type="text" id="updateCache" name="cache" required>

    <label for="updateCore">Core:</label>
    <input type="number" id="updateCore" name="core" required>

    <label for="updateThread">Thread:</label>
    <input type="number" id="updateThread" name="thread" required>

    <label for="updateFreq">Frequency:</label>
    <input type="text" id="updateFreq" name="freq" required>

    <label for="updateImg">Image File (name only):</label>
    <input type="text" id="updateImg" name="img" required>

    <button type="submit">Update Processor</button>
</form>

</div>

<div id="response"></div>

<script>
    // Handle form submission for adding new processor via AJAX
    $('#addProcessorForm').on('submit', function(e) {
        e.preventDefault();  // Prevent form from reloading page
        
        var manufacturer = $('#manufacturer').val();
        var series = $('#series').val();
        var ig = $('#ig').val();
        var cache = $('#cache').val();
        var core = $('#core').val();
        var thread = $('#thread').val();
        var freq = $('#freq').val();
        var img = $('#img').val();
        
        $.ajax({
            type: 'POST',
            url: 'add_processor.php',
            data: { manufacturer: manufacturer, series: series, ig: ig, cache: cache, core: core, thread: thread, freq: freq, img: img },
            success: function(response) {
                var responseData = JSON.parse(response);
                $('#response').html(responseData.message);

                if (responseData.message.includes("success")) {
                    var newRow = '<tr id="processor-'+responseData.newId+'"><td>' + responseData.newId + '</td><td>' + manufacturer + '</td><td>' + series + '</td><td>' + ig + '</td><td>' + cache + '</td><td>' + core + '</td><td>' + thread + '</td><td>' + freq + '</td><td><img src="images/' + img + '" alt="' + series + '" width="50"></td><td><button class="editProcessor" data-id="' + responseData.newId + '">Edit</button> <button class="deleteProcessor" data-id="' + responseData.newId + '">Delete</button></td></tr>';
                    $('#processorTable').append(newRow);
                    $('#addProcessorForm')[0].reset();
                    $('.adder').toggleClass("active")
                }
            }
        });
    });

    // Handle the 'Edit' button click to prefill the update form
    $(document).on('click', '.editProcessor', function() {
        $(".updater").toggleClass("active")
        var processorId = $(this).data('id');
        
        // Find processor data by ID and fill the update form
        $.ajax({
            type: 'GET',
            url: 'get_processor.php',
            data: { id: processorId },
            success: function(response) {
                var processor = JSON.parse(response);
                $('#updateProcessorId').val(processor.id);
                $('#updateManufacturer').val(processor.manufacturer);
                $('#updateSeries').val(processor.series);
                $('#updateIg').val(processor.ig);
                $('#updateCache').val(processor.cache);
                $('#updateCore').val(processor.core);
                $('#updateThread').val(processor.thread);
                $('#updateFreq').val(processor.freq);
                $('#updateImg').val(processor.img);
            }
        });
    });

    // Handle form submission for updating processor via AJAX
    $('#updateProcessorForm').on('submit', function(e) {
        e.preventDefault();  // Prevent form from reloading page
        
        var processorId = $('#updateProcessorId').val();
        var manufacturer = $('#updateManufacturer').val();
        var series = $('#updateSeries').val();
        var ig = $('#updateIg').val();
        var cache = $('#updateCache').val();
        var core = $('#updateCore').val();
        var thread = $('#updateThread').val();
        var freq = $('#updateFreq').val();
        var img = $('#updateImg').val();
        
        $.ajax({
            type: 'POST',
            url: 'update_processor.php',
            data: { id: processorId, manufacturer: manufacturer, series: series, ig: ig, cache: cache, core: core, thread: thread, freq: freq, img: img },
            success: function(response) {
                var responseData = JSON.parse(response);
                $('#response').html(responseData.message);

                if (responseData.message.includes("success")) {
                    $(".updater").toggleClass("active")
                    // Update the table row with new processor information
                    $('#processor-' + processorId).html('<td>' + processorId + '</td><td>' + manufacturer + '</td><td>' + series + '</td><td>' + ig + '</td><td>' + cache + '</td><td>' + core + '</td><td>' + thread + '</td><td>' + freq + '</td><td><img src="images/' + img + '" alt="' + series + '" width="50"></td><td><button class="editProcessor" data-id="' + processorId + '">Edit</button> <button class="deleteProcessor" data-id="' + processorId + '">Delete</button></td>');
                    $('#updateProcessorForm')[0].reset();
                }
            }
        });
    });

    // Handle Delete button click via AJAX
    $(document).on('click', '.deleteProcessor', function() {
        var processorId = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this processor?')) {
            $.ajax({
                type: 'POST',
                url: 'delete_processor.php',
                data: { id: processorId },
                success: function(response) {
                    var responseData = JSON.parse(response);
                    $('#response').html(responseData.message);

                    if (responseData.message.includes("success")) {
                        // Remove the row from the table
                        console.log(processorId)
                        $('#processor-' + processorId).remove();
                    }
                }
            });
        }
    });
</script>
<script src="util.js"></script>
</body>
</html>
