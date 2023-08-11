<?php
session_start();
$token = $_SESSION['token'];
$validToken = false;

// Validate the token and check expiry
function validateToken($token)
{
    include "db.php";
    // Retrieve token information from the database
    $selectQuery = "SELECT token_expiry FROM users WHERE reset_token = '$token'";

    $result = $connection->query($selectQuery);
    $row = $result->fetch_assoc();

    if ($row) {
        $expiration = strtotime($row['token_expiry']);
        $currentTimestamp = time();

        if ($currentTimestamp <= $expiration) {
            // Token is valid and not expired
            return true;
        }
    }

    // Token is invalid or expired
    return false;
}

$validToken = validateToken($token);

if (!$validToken) {
    // Token has expired, redirect to index.php
    $_SESSION['error'] = "Invalid Token";
    header('Location: index.php');
    exit;
} else {
    $_SESSION['error'] = "";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <div class="card w-50 mx-auto my-5 rounded">
        <div class="card-body my-5 p-5">
            <h3 class="mb-3">Reset Password</h3>
            <form id="resetForm">
                <!-- password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password">
                </div>
                <!-- cnf password -->
                <div class="mb-5">
                    <label for="cnf-password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="cnf-password">
                    <p id="password-error" class="text-danger"></p>
                </div>
                <button class="btn btn-primary mb-3">RESET PASSWORD</button>
            </form>
            <a class="btn btn-success" href="./index.php">Back</a>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#resetForm').submit(function(e) {
                e.preventDefault();

                let password = $('#password').val();
                let confirmPassword = $('#cnf-password').val();

                // passwords are equal
                if (password !== confirmPassword) {

                    $("#password-error").html('Passwords do not match. Please try again.');
                    return;
                } else {

                    let formData = {
                        token: "<?php echo $token; ?>",
                        password: password,
                        confirm_password: confirmPassword
                    };
                    // submitting ajax
                    $.ajax({
                        type: 'POST',
                        url: 'password_update.php',
                        data: formData,
                        dataType: "json",
                        success: function(response) {
                            if (response.status === 1) {
                                // redirect after update
                                window.location.href = 'index.php';
                                alert('Successfully Updated');
                            } else {
                                // Password reset failed
                                $("#password-error").html(response.message);
                            }

                            console.log(response);
                        },
                        error: function(error) {
                            console.error('Error submitting form:', error);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>