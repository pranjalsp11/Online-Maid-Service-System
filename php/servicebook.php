<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure that the form is submitted and process the form data

    // Replace the database connection details with your own
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

    // Sanitize the form data to prevent SQL injection
    
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $service = mysqli_real_escape_string($conn, $_POST['service']);
    $instructions = mysqli_real_escape_string($conn, $_POST['instructions']);
    session_start();
    $_SESSION['service']=$service;
    $_SESSION['cusName']=$name;
    $_SESSION['cusMobile']=$mobile;
    $_SESSION['cusAddress']=$address;
    $_SESSION['cusServiceType']=$service;

    // Insert the common form data into the database
    //$sql = "INSERT INTO service( Name, Email,MobileNo, Address, Date, Time, Instruction,Service)
         //   VALUES ('$email', '$name', '$mobile', '$address', '$date', '$time', '$instructions','$service')";

    if (!$conn->connect_error)  {
        // Booking data inserted successfully
       
        // Insert addition al data based on the selected service type
        if ($service === "cleaning") {
            $houseInfo = mysqli_real_escape_string($conn, $_POST['houseInfo']);
            $numRooms = mysqli_real_escape_string($conn, $_POST['numRooms']);
            $numBathrooms= mysqli_real_escape_string($conn, $_POST['numBathrooms']);
            $cleaningType = mysqli_real_escape_string($conn, $_POST['cleaningType']);
            function updateEstimatedPrice($numRooms, $numBathrooms, $cleaningType) {
                // Perform the calculations
                $pricePerRoom = 50;
                $pricePerBathroom = 30;
                $totalPrice = ($numRooms * $pricePerRoom) + ($numBathrooms * $pricePerBathroom);
            
                if ($cleaningType === 'deep') {
                    $totalPrice += 50;
                } else if ($cleaningType === 'move') {
                    $totalPrice += 100;
                }
            
                return $totalPrice;
            }
            $estimatedPrice = updateEstimatedPrice($numRooms, $numBathrooms, $cleaningType);
            $sqlCleaning = "INSERT INTO cleaning (Name, Email,MobileNo, Address, Date, Time, Instruction,HouseInfo,NoRooms,NoBathroom,Type,Price)
            VALUES ('$name','$email', '$mobile', '$address', '$date', '$time', '$instructions','$houseInfo','$numRooms','$numBathrooms','$cleaningType','$estimatedPrice')";
            
           if ($conn->query($sqlCleaning) == TRUE) {
            header("Location: displaymaid.php");
            exit();
           }
        }
        elseif ($service === "cooking") {
            $foodType = mysqli_real_escape_string($conn, $_POST['foodType']);
            $numPeople = mysqli_real_escape_string($conn, $_POST['numPeople']);
            $specialRequests = mysqli_real_escape_string($conn, $_POST['specialRequests']);
            
            function updateEstimatedPrice($numPeople) {
                // Perform the calculations
                $basePricePerPerson = 20;
                $totalPrice = $numPeople * $basePricePerPerson;            
                return $totalPrice;
            }
            $estimatedPrice = updateEstimatedPrice($numPeople);
            $sqlCooking = "INSERT INTO cook (Name, Email,MobileNo, Address, Date, Time, Instruction,FoodType,NoPeople,Request,Price)
                            VALUES ('$name','$email', '$mobile', '$address', '$date', '$time', '$instructions','$foodType','$numPeople',' $specialRequests','$estimatedPrice')";

            if ($conn->query($sqlCooking) == TRUE) {
                header("Location: displaymaid.php");
                exit();
            }
        }
        elseif ($service === "baby") {
            $ageOfBaby = mysqli_real_escape_string($conn, $_POST['ageOfBaby']);
            $numHours = mysqli_real_escape_string($conn, $_POST['numHours']);
            $additionalServices = mysqli_real_escape_string($conn, $_POST['additionalServices']);
            function updateEstimatedPrice($numHours) {
                // Perform the calculations
                $basePricePerHour = 25;
                $totalPrice = $numHours * $basePricePerHour;            
                return $totalPrice;
            }
            $estimatedPrice = updateEstimatedPrice($numHours);
            $sqlbaby = "INSERT INTO babycare (Name, Email,MobileNo, Address, Date, Time, Instruction,Age,Hours,AddService,Price)
                            VALUES ('$name','$email', '$mobile', '$address', '$date', '$time', '$instructions','$ageOfBaby','$numHours','$additionalServices','$estimatedPrice')";

            if ($conn->query($sqlbaby) == TRUE) {
                header("Location: displaymaid.php");
                exit();
            }
        }
        elseif ($service === "elder") {
            $age= mysqli_real_escape_string($conn, $_POST['age']);
            $medicalConditions = mysqli_real_escape_string($conn, $_POST['medicalConditions']);
            $numHours = mysqli_real_escape_string($conn, $_POST['numHours']);
            $additionalServices = mysqli_real_escape_string($conn, $_POST['additionalServices']);
            function updateEstimatedPrice($numHours) {
                // Perform the calculations
                $basePricePerHour = 30;
               $totalPrice = $numHours * $basePricePerHour;            
                return $totalPrice;
            }
            $estimatedPrice = updateEstimatedPrice($numHours);
            $sqlelder = "INSERT INTO elder (Name, Email,MobileNo, Address, Date, Time, Instruction,Age,Medical,Hours,AddService,Price)
                            VALUES ('$name','$email', '$mobile', '$address', '$date', '$time', '$instructions','$age','$medicalConditions','$numHours','$additionalServices','$estimatedPrice')";

            if ($conn->query($sqlelder) == TRUE) {
                header("Location: displaymaid.php");
    exit();
            }
        }
        elseif ($service === "office") {
            $officeSize = mysqli_real_escape_string($conn, $_POST['officeSize']);
            $numDesks = mysqli_real_escape_string($conn, $_POST['numDesks']);
            $numMeetingRooms = mysqli_real_escape_string($conn, $_POST['numMeetingRooms']);
            $cleaningFrequency= mysqli_real_escape_string($conn, $_POST['cleaningFrequency']);
            function updateEstimatedPrice($officeSize,$numDesks,$numMeetingRooms,$cleaningFrequency) {
                // Perform the calculations
                 $pricePerSqFt = 0.10; // $0.10 per sq. ft.
                 $pricePerDesk = 10;   // $10 per desk
                 $pricePerMeetingRoom = 30;
               $totalPrice =($officeSize * $pricePerSqFt) + ($numDesks * $pricePerDesk) + ($numMeetingRooms * $pricePerMeetingRoom);       
               if ($cleaningFrequency === 'daily') {
                $totalPrice *= 30; // Assuming monthly cleaning for daily frequency
                } else if ($cleaningFrequency === 'weekly') {
                     $totalPrice *= 4;  // Assuming monthly cleaning for weekly frequency
                } else if ($cleaningFrequency === 'biweekly') {
                    $totalPrice *= 2;  // Assuming monthly cleaning for bi-weekly frequency
                }
                  return $totalPrice;
              }
            $estimatedPrice = updateEstimatedPrice($numHours,$numDesks,$numMeetingRooms,$cleaningFrequency);
            $sqloffice = "INSERT INTO office (Name, Email,MobileNo, Address, Date, Time, Instruction,Size,NoDesks,MeetingRoom,Frequency,Price)
                            VALUES ('$name','$email', '$mobile', '$address', '$date', '$time', '$instructions','$officeSize','$numDesks','$numMeetingRooms','$cleaningFrequency',' $estimatedPrice ')";

            if ($conn->query($sqloffice) == TRUE) {
                header("Location: displaymaid.php");
                 exit();
            }
        }
        elseif ($service === "driver") {
            $destination= mysqli_real_escape_string($conn, $_POST['destination']);
            $numHours= mysqli_real_escape_string($conn, $_POST['numHours']);
            $vehicleType= mysqli_real_escape_string($conn, $_POST['vehicleType']);
            function updateEstimatedPrice($numHours) {
                // Perform the calculations
                $basePricePerHour = 40; 
                $totalPrice = $numHours * $basePricePerHour;         
                return $totalPrice;
            }
            $estimatedPrice = updateEstimatedPrice($numHours);
            $sqldriver = "INSERT INTO driver (Name, Email,MobileNo, Address, Date, Time, Instruction,Destination,Hours,VehicleType,Price)
                            VALUES ('$name','$email', '$mobile', '$address', '$date', '$time', '$instructions','$destination','$numHours','$vehicleType','$estimatedPrice')";

            if ($conn->query($sqldriver) == TRUE) {
                header("Location: displaymaid.php");
                exit();
            }
        }
        
    } else {
        // Error occurred while inserting data
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connectione
    $conn->close();
    header("Location: ../index.html");
    exit();
}
?>
