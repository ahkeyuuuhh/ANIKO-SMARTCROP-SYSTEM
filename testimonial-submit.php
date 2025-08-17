<?php
session_start();

// Check if user is logged in with Google
if (!isset($_SESSION['email'])) {
    // If not logged in, redirect to login page
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Testimonial</title>
    <link rel="stylesheet" href="styles.css"> <!-- optionaaAAAAl -->
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> 👋</h2>
    <img src="<?php echo htmlspecialchars($_SESSION['picture']); ?>" 
         alt="Profile Picture" width="80" height="80">

    <h3>Submit your Testimonial</h3>
    <form action="save_testimonial.php" method="POST">
        <textarea name="testimonial" rows="5" cols="40" placeholder="Write your testimonial here..." required></textarea><br><br>
        <button type="submit">Submit</button>
    </form>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
