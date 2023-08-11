<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment 2 Forgot Password Concept</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <div class="card w-50 mx-auto my-5 rounded">
        <div class="card-body my-5 p-5">
            <h3 class="mb-3">Reset Password</h3>
            <!-- form -->
            <form id="resetForm">
                <!-- email -->
                <div class="mb-5">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" placeholder="Your Email">
                </div>
                <p class="mb-3 text-danger" id="emailCheckResult"></p>
                <button class="btn btn-primary" id="reset-button">RESET PASSWORD</button>
            </form>

            <p class="my-3 text-danger" id="reset-link"></p>
            <a href="#" class="btn btn-success float-end">Back to Login</a>
            <p class="text-danger" id="token-error"><?php echo $_SESSION['error']; ?></p>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#resetForm').submit(function(e) {
                e.preventDefault();

                let email = $('#email').val();
                // ajax submit
                $.ajax({
                    type: 'POST',
                    url: 'email_check.php',
                    dataType: "json",
                    data: {
                        email: email
                    },
                    success: function(response) {
                        if (response.status === 0) {
                            $('#emailCheckResult').html(response.message);
                            $("#reset-link").empty();
                        } else {
                            $('#emailCheckResult').empty();
                            $("#token-error").empty();
                            let responseData = response.message.split(':');
                            let token = responseData[1].trim();
                            let expiration = responseData[2].trim();

                            // generating reset link
                            generateResetLink(token);
                            $('#reset-link a').click(function(event) {
                                event.preventDefault();
                                window.location.href = 'reset.php';
                            });
                        }
                    },
                    error: function(error) {
                        console.error('Error checking email:', error);
                    }
                });

            });


            // reset link function
            function generateResetLink(token) {
                let resetLink = 'reset.php';
                let linkHtml = '<a href="' + resetLink + '">Reset Password Link</a>';

                $('#reset-link').html(linkHtml);
            }
        });
    </script>
</body>

</html>