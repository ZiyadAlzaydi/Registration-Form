<!DOCTYPE html>
<html>
<head>
    <title>ZiyadForm</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // JavaScript function to handle the toggle button click
        function toggleStatus(id) {
            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Configure the request: POST to toggle_status.php
            xhr.open("POST", "toggle_status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Define what happens on successful data reception
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Parse the JSON response
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Update the status text in the table
                        var statusElement = document.getElementById('status-' + id);
                        if (statusElement) {
                            statusElement.textContent = response.newStatus;
                        }
                        console.log("Status toggled successfully for ID: " + id);
                    } else {
                        console.error("Error toggling status: " + response.message);
                    }
                }
            };

            // Send the request with the ID
            xhr.send("id=" + id);
        }
    </script>
</head>
<body>

    <div class="container">
        <!-- Existing Form Section -->
        <form action="submit.php" method="post">
            
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Tom" required>
            </div>
            <div>
                <label for="age">Age:</label>
                <input type="text" id="age" name="age" placeholder="21" required>
            </div>
            <div>
                <input type="Submit" value="Submit">
            </div>
        </form>

        <!-- Data Display Section -->
        <div class="data-table-container">
            <h2>Database Records</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Database configuration (same as submit.php)
                    $servername = "localhost";
                    $username = "root";
                    $dbname = "mydatabase";
                    $password = "";

                    // Connect to DB
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Fetch data from the 'info' table
                    $sql = "SELECT ID, Name, Age, Status FROM info";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["ID"] . "</td>";
                            echo "<td>" . $row["Name"] . "</td>";
                            echo "<td>" . $row["Age"] . "</td>";
                            // Display status and add an ID for JS update
                            echo "<td id='status-" . $row["ID"] . "'>" . $row["Status"] . "</td>";
                            // Add the toggle button
                            echo "<td><button onclick='toggleStatus(" . $row["ID"] . ")' class='toggle-btn'>Toggle</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No records found</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
