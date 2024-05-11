<?php
  session_start();
  if (!isset($_SESSION["username"])) {
     header("Location: login.php");
  }

  include("connection.php");

  if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];

    // Fetch student data based on NIM
    $query = "SELECT * FROM student WHERE nim = '$nim'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Error: " . mysqli_errno($connection) . " - " . mysqli_error($connection));
    }

    // Check if student exists
    if (mysqli_num_rows($result) == 1) {
        // Delete the student record
        $delete_query = "DELETE FROM student WHERE nim = '$nim'";
        $delete_result = mysqli_query($connection, $delete_query);

        if ($delete_result) {
            $message = "Data mahasiswa dengan NIM $nim berhasil dihapus";
            $message = urlencode($message);
            header("Location: student_view.php?message={$message}");
            exit(); // Stop script execution after redirection
        } else {
            die("Query gagal dijalankan: " . mysqli_errno($connection) . " - " . mysqli_error($connection));
        }
    } else {
        echo "Mahasiswa dengan NIM $nim tidak ditemukan.";
        exit(); // Stop script execution if student is not found
    }
} else {
    echo "NIM parameter is missing.";
    exit(); // Stop script execution if NIM parameter is missing
}

mysqli_close($connection);
?>