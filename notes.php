<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dodawanie notatki
    if (isset($_POST['add_note'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];

        $sql = "INSERT INTO notes (username, title, content, created_at, modified_at)
                VALUES ('$username', '$title', '$content', NOW(), NOW())";

        if ($conn->query($sql) === TRUE) {
            header("Location: notes.php");
        } else {
            $error = "Operation failed";
        }
    }

    // Edycja notatki
    if (isset($_POST['edit_note'])) {
        $note_id = $_POST['note_id'];
        $title = $_POST['title_edit'];  // Użyj zmienionej nazwy pola
        $content = $_POST['content_edit'];  // Użyj zmienionej nazwy pola

        // Sprawdź, czy notatka należy do aktualnie zalogowanego użytkownika
        $checkOwnershipSql = "SELECT * FROM notes WHERE id=$note_id AND username='$username'";
        $checkOwnershipResult = $conn->query($checkOwnershipSql);

        if ($checkOwnershipResult->num_rows == 1) {
            // Uaktualnij notatkę
            $updateNoteSql = "UPDATE notes SET title='$title', content='$content', modified_at=NOW() WHERE id=$note_id";
            if ($conn->query($updateNoteSql) === TRUE) {
                header("Location: notes.php");
            } else {
                $error = "Operation failed";
            }
        } else {
            $error = "You do not have permission to edit this note.";
        }
    }

    // Usuwanie notatki
    if (isset($_POST['delete_note'])) {
        $note_id = $_POST['note_id'];

        $sql = "DELETE FROM notes WHERE id=$note_id AND username='$username'";

        if ($conn->query($sql) === TRUE) {
            header("Location: notes.php");
        } else {
            $error = "Operation failed";
        }
    }
}

// Pobieranie notatek użytkownika
$sql = "SELECT id, title, content, modified_at FROM notes WHERE username = '$username'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $username; ?>!</h2>

        <form method="post" action="">
            <label for="title">Title:</label>
            <input type="text" name="title" required>
            <br>
            <label for="content">Content:</label>
            <textarea name="content" required></textarea>
            <br>
            <button type="submit" name="add_note">Add Note</button>
        </form>

        <?php
        if (isset($error)) echo "<p class='error'>$error</p>";

        if ($result->num_rows > 0) {
            echo "<h3>Your Notes:</h3>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='note'>
                        <h4>{$row['title']}</h4>
                        <p>Modified at: {$row['modified_at']}</p>
                        <form method='post' action=''>
                            <input type='hidden' name='note_id' value='{$row['id']}'>
                            <label for='title_edit'>Title:</label>
                            <input type='text' name='title_edit' value='{$row['title']}' required>
                            <br>
                            <label for='content_edit'>Content:</label>
                            <textarea name='content_edit' required>{$row['content']}</textarea>
                            <br>
                            <button type='submit' name='edit_note'>Edit</button>
                            <button type='submit' name='delete_note'>Delete</button>
                        </form>
                    </div>";
            }
        } else {
            echo "<p>No notes found.</p>";
        }
        ?>
    </div>
</body>
</html>
