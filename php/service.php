<?php
session_start();

// Check if the 'username' key exists in the $_SESSION array and has a non-null value
if (isset($_SESSION['username'])) {
    $name1 = $_SESSION['username'];
   $servername = "localhost";
   $username_db = "root";
   $password_db = "";
   $database = "maidproject";

   // Create a connection
   $conn = new mysqli($servername, $username_db, $password_db, $database);

   // Check the connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }

   // Prepare and execute the query
   
   $sql = "SELECT * FROM user WHERE Name = ?";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("s", $name1,);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows >= 1) {
       // Get the maid's information
       $maidInfo = $result->fetch_assoc();
       $name = $maidInfo['Name'];
       $mobile = $maidInfo['Mobile'];
       $address= $maidInfo['Address'];
       $email = $maidInfo['Email'];

   }
   
   $stmt->close();
   $conn->close();
} else {
    echo "Please log in to access this page.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Service Booking Form</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 50px;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            color: #333;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-info {
            font-size: 14px;
            color: #666;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        @media (min-width: 768px) {
            .form-row {
                display: flex;
                flex-wrap: wrap;
            }

            .form-row .form-group {
                flex: 1;
                margin-right: 15px;
            }

            .form-row .form-group:last-child {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Service Booking Form</h2>
            <form action="http://localhost/Maid-Services-Web-developement-project-main/Maid-Services-Web-developement-project-main/php/servicebook.php" method="POST">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?php echo isset($name)?$name: ''; ?>"required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo isset($email)?$email: ''; ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="mobile">Mobile No:</label>
                        <input type="tel" class="form-control" name="mobile" id="mobile" value="<?php echo isset($mobile)?$mobile: ''; ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" name="address" id="address" value="<?php echo  isset($address)?$address: ''; ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="date">Preferred Date:</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="time">Preferred Time:</label>
                        <input type="time" id="time" name="time" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="instructions">Special Instructions:</label>
                    <textarea class="form-control" name="instructions" id="instructions" rows="4"></textarea>
                    <p class="form-info">Please provide any special instructions or additional details here.</p>
                </div>
                <div class="form-group">
                    <label for="service">Type of Service:</label>
                    <select class="form-control" name="service" id="service" onchange="showServiceForm()" required>
                        <option value="" disabled selected>Select a service</option>
                        <option value="cleaning">Cleaning</option>
                        <option value="cooking">Cooking</option>
                        <option value="baby">Baby Sitting</option>
                        <option value="elder">Elder Care</option>
                        <option value="office">Office cleaning</option>
                        <option value="driver">Driver</option>
                        <!-- Add more service options here -->
                    </select>
                </div>

                <!-- Dynamic form section will be inserted here -->
                <div class="dynamic-form" id="formContainer"></div>

                <input type="submit" class="btn btn-primary" value="Book Service">
            </form>
        </div>
    </div>

    <script>
        function showServiceForm() {
            const serviceType = document.getElementById("service").value;
            const formContainer = document.getElementById("formContainer");

            // Remove any existing dynamic form sections
            formContainer.innerHTML = "";

            // Create and append dynamic form sections based on selected service
            if (serviceType === "cleaning") {
                formContainer.innerHTML = `
                <div class="form-group">
                    <label for="houseInfo">House Information:</label>
                    <textarea class="form-control" name="houseInfo" id="houseInfo" required></textarea>
                </div>
                <p class="form-info">Please provide details about the cleaning service you need.</p>

                <div class="form-group">
                    <label for="numRooms">Number of Rooms:</label>
                    <input type="number" class="form-control" name="numRooms" id="numRooms" min="1" required>
                </div>

                <div class="form-group">
                    <label for="numBathrooms">Number of Bathrooms:</label>
                    <input type="number" class="form-control" name="numBathrooms" id="numBathrooms" min="1" required>
                </div>

                <div class="form-group">
                    <label for="cleaningType">Cleaning Type:</label>
                    <select class="form-control" name="cleaningType" id="cleaningType" required>
                        <option value="" disabled selected>Select a cleaning type</option>
                        <option value="regular">Regular Cleaning</option>
                        <option value="deep">Deep Cleaning</option>
                        <option value="move">Move-in/Move-out Cleaning</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="estimatedPrice">Estimated Price:</label>
                    <input type="text" class="form-control" name="estimatedPrice" id="estimatedPrice" readonly>
                </div>
                `;
                document.getElementById('numRooms').addEventListener('input', updateEstimatedPrice);
                document.getElementById('numBathrooms').addEventListener('input', updateEstimatedPrice);
                document.getElementById('cleaningType').addEventListener('change', updateEstimatedPrice);
            } else if (serviceType === "cooking") {
                formContainer.innerHTML = `
                <div class="form-group">
                    <label for="foodType">Type of Food:</label>
                    <input type="text" class="form-control" name="foodType" id="foodType" required>
                </div>
                <p class="form-info">Please specify the type of food you want to be prepared.</p>

                <div class="form-group">
                    <label for="numPeople">Number of People:</label>
                    <input type="number" class="form-control" name="numPeople" id="numPeople" min="1" required>
                </div>
                <div class="form-group">
                    <label for="specialRequests">Special Requests:</label>
                    <textarea class="form-control" name="specialRequests" id="specialRequests" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="estimatedPrice">Estimated Price:</label>
                    <input type="text" class="form-control" name="estimatedPrice" id="estimatedPrice" readonly>
                </div>
                `;
                document.getElementById('numPeople').addEventListener('input', updateEstimatedPricecook);
            }
            else if (serviceType === "baby") {
                formContainer.innerHTML = `
                <div class="form-group">
                    <label for="ageOfBaby">Age of Baby:</label>
                    <input type="text" class="form-control" name="ageOfBaby" id="ageOfBaby" required>
                </div>
                <p class="form-info">Please specify the age of the baby.</p>

                <div class="form-group">
                    <label for="numHours">Number of Hours:</label>
                    <input type="number" class="form-control" name="numHours" id="numHours" min="1" required>
                </div>
                <div class="form-group">
                    <label for="additionalServices">Additional Services:</label>
                    <textarea class="form-control" name="additionalServices" id="additionalServices" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="estimatedPrice">Estimated Price:</label>
                    <input type="text" class="form-control" name="estimatedPrice" id="estimatedPrice" readonly>
                </div>
                `;
                document.getElementById('numHours').addEventListener('input', updateEstimatedPricebaby);
            }
            else if (serviceType === "elder") {
                formContainer.innerHTML = `
                <div class="form-group">
                    <label for="age">Age of Elder:</label>
                    <input type="number" class="form-control" name="age" id="age" min="1" required>
                </div>
                <p class="form-info">Please specify the age of the elder.</p>

                <div class="form-group">
                    <label for="medicalConditions">Medical Conditions:</label>
                    <textarea class="form-control" name="medicalConditions" id="medicalConditions" rows="4" required></textarea>
                </div>
                <p class="form-info">Please provide details about any medical conditions or special care requirements for the elder.</p>

                <div class="form-group">
                   <label for="numHours">Number of Hours:</label>
                   <input type="number" class="form-control" name="numHours" id="numHours" min="1" required>
                </div>

                <div class="form-group">
                  <label for="additionalServices">Additional Services:</label>
                    <textarea class="form-control" name="additionalServices" id="additionalServices" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="estimatedPrice">Estimated Price:</label>
                    <input type="text" class="form-control" name="estimatedPrice" id="estimatedPrice" readonly>
                </div>
                `;
                document.getElementById('numHours').addEventListener('input', updateEstimatedPriceelder);
            }
            else if (serviceType === "office") {
                formContainer.innerHTML = `
                <div class="form-group">
                    <label for="officeSize">Office Size (in sq. ft.):</label>
                    <input type="number" class="form-control" name="officeSize" id="officeSize" min="1" required>
                </div>
                <p class="form-info">Please specify the size of the office space that needs cleaning.</p>

                <div class="form-group">
                    <label for="numDesks">Number of Desks:</label>
                    <input type="number" class="form-control" name="numDesks" id="numDesks" min="1" required>
                </div>
                <div class="form-group">
                    <label for="numMeetingRooms">Number of Meeting Rooms:</label>
                    <input type="number" class="form-control" name="numMeetingRooms" id="numMeetingRooms" min="0" required>
                </div>

                <div class="form-group">
                    <label for="cleaningFrequency">Cleaning Frequency:</label>
                    <select class="form-control" name="cleaningFrequency" id="cleaningFrequency" required>
                        <option value="" disabled selected>Select a cleaning frequency</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="biweekly">Bi-weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="estimatedPrice">Estimated Price:</label>
                    <input type="text" class="form-control" name="estimatedPrice" id="estimatedPrice" readonly>
                </div>

                `;
                document.getElementById('officeSize').addEventListener('input', updateEstimatedPriceoffice);
                document.getElementById('numDesks').addEventListener('input', updateEstimatedPriceoffice);
                document.getElementById('numMeetingRooms').addEventListener('input', updateEstimatedPriceoffice);
            }
            else if (serviceType === "driver") {
                formContainer.innerHTML = `
                <div class="form-group">
                    <label for="destination">Destination:</label>
                    <input type="text" class="form-control" name="destination" id="destination" required>
                </div>
                <p class="form-info">Please specify the destination or route for the driver service.</p>

                <div class="form-group">
                    <label for="numHours">Number of Hours:</label>
                    <input type="number" class="form-control" name="numHours" id="numHours" min="1" required>
                </div>
                <div class="form-group">
                    <label for="vehicleType">Vehicle Type:</label>
                    <select class="form-control" name="vehicleType" id="vehicleType" required>
                        <option value="" disabled selected>Select a vehicle type</option>
                        <option value="sedan">Sedan</option>
                        <option value="suv">SUV</option>
                        <option value="van">Van</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="estimatedPrice">Estimated Price:</label>
                    <input type="text" class="form-control" name="estimatedPrice" id="estimatedPrice" readonly>
                </div>
                `;
                document.getElementById('numHours').addEventListener('input', updateEstimatedPricedriver);
            }
            // Add more conditions for other service types as needed
        }
        function updateEstimatedPrice() {
        const numRooms = parseInt(document.getElementById('numRooms').value) || 0;
        const numBathrooms = parseInt(document.getElementById('numBathrooms').value) || 0;
        const cleaningType = document.getElementById('cleaningType').value;
        let pricePerRoom = 50;
        let pricePerBathroom = 30;
        let totalPrice = (numRooms * pricePerRoom) + (numBathrooms * pricePerBathroom);

        if (cleaningType === 'deep') {
            totalPrice += 50;
        } else if (cleaningType === 'move') {
            totalPrice += 100;
        }

        document.getElementById('estimatedPrice').value = '$' + totalPrice.toFixed(2);
        }
        function updateEstimatedPricecook() {
        const numPeople = parseInt(document.getElementById('numPeople').value) || 0;
        const basePricePerPerson = 20;
        const totalPrice = numPeople * basePricePerPerson;

        document.getElementById('estimatedPrice').value = '$' + totalPrice.toFixed(2);
        }
        function updateEstimatedPricebaby() {
        const numHours = parseInt(document.getElementById('numHours').value) || 0;
        const basePricePerHour = 25;
        const totalPrice = numHours * basePricePerHour;

        document.getElementById('estimatedPrice').value = '$' + totalPrice.toFixed(2);
    }
    function updateEstimatedPriceelder() {
        const numHours = parseInt(document.getElementById('numHours').value) || 0;
        const basePricePerHour = 30;
        const totalPrice = numHours * basePricePerHour;

        document.getElementById('estimatedPrice').value = '$' + totalPrice.toFixed(2);
    }
    function updateEstimatedPriceoffice() {
        const officeSize = parseInt(document.getElementById('officeSize').value) || 0;
        const numDesks = parseInt(document.getElementById('numDesks').value) || 0;
        const numMeetingRooms = parseInt(document.getElementById('numMeetingRooms').value) || 0;
        const cleaningFrequency = document.getElementById('cleaningFrequency').value;

        const pricePerSqFt = 0.10; // $0.10 per sq. ft.
        const pricePerDesk = 10;   // $10 per desk
        const pricePerMeetingRoom = 30; // $30 per meeting room

        let totalPrice = (officeSize * pricePerSqFt) + (numDesks * pricePerDesk) + (numMeetingRooms * pricePerMeetingRoom);

        if (cleaningFrequency === 'daily') {
            totalPrice *= 30; // Assuming monthly cleaning for daily frequency
        } else if (cleaningFrequency === 'weekly') {
            totalPrice *= 4;  // Assuming monthly cleaning for weekly frequency
        } else if (cleaningFrequency === 'biweekly') {
            totalPrice *= 2;  // Assuming monthly cleaning for bi-weekly frequency
        } // For monthly frequency, no adjustment is needed
        document.getElementById('estimatedPrice').value = '$' + totalPrice.toFixed(2);
    }
    function updateEstimatedPricedriver() {
        const numHours = parseInt(document.getElementById('numHours').value) || 0;
        const basePricePerHour = 40; // $40 per hour
        const totalPrice = numHours * basePricePerHour;

        document.getElementById('estimatedPrice').value = '$' + totalPrice.toFixed(2);
    }
    </script>
</body>
</html>
