<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'CONFIG/config.php';
include 'INCLUDE/admin_header.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: admin_login.php");
    exit();
}

$reg_message = "";
if (isset($_POST['register_admin'])) {
    $new_username = trim($_POST['username']);
    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($new_username) || empty($new_password) || empty($confirm_password)) {
        $reg_message = '<div class="alert alert-danger">All fields are required.</div>';
    } elseif ($new_password !== $confirm_password) {
        $reg_message = '<div class="alert alert-danger">Passwords do not match.</div>';
    } else {
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $con->prepare("INSERT INTO admin_accounts (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $new_username, $hashedPassword);
        try {
            $stmt->execute();
            $reg_message = '<div class="alert alert-success">New admin registered successfully!</div>';
        } catch (mysqli_sql_exception $e) {
            $reg_message = '<div class="alert alert-danger">Username already exists!</div>';
        }
        $stmt->close();
    }
}

function deleteImage($table, $id, $con) {
    $stmt = $con->prepare("SELECT image_path FROM $table WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagePath);
    $found = $stmt->fetch();
    $stmt->close();

    if ($found && $imagePath && file_exists($imagePath)) {
        unlink($imagePath);
    }
    $stmt = $con->prepare("DELETE FROM $table WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

if (isset($_GET['delete_team_id'])) {
    deleteImage('team_members', intval($_GET['delete_team_id']), $con);
    header("Location: admin_index.php");
    exit;
}
if (isset($_GET['delete_why_aniko_id'])) {
    deleteImage('why_aniko_images', intval($_GET['delete_why_aniko_id']), $con);
    header("Location: admin_index.php?deleted=1");
    exit;
}
if (isset($_GET['delete_download_id'])) {
    deleteImage('download_images', intval($_GET['delete_download_id']), $con);
    header("Location: admin_index.php");
    exit;
}
if (isset($_GET['delete_benefits_id'])) {
    deleteImage('benefits_images', intval($_GET['delete_benefits_id']), $con);
    header("Location: admin_index.php");
    exit;
}
if (isset($_GET['delete_id'])) {
    deleteImage('home_images', intval($_GET['delete_id']), $con);
    header("Location: admin_index.php");
    exit;
}

function handleAction($action, $table, $con) {
    if (isset($_GET['action']) && $_GET['action'] === $action && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if ($action === 'approve') {
            $stmt = $con->prepare("UPDATE testimonials SET status = 'approved' WHERE id = ?");
        } else {
            $stmt = $con->prepare("DELETE FROM $table WHERE id = ?");
        }
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = ucfirst($action) . "d successfully!";
        } else {
            $_SESSION['message'] = "Error " . $action . "ing.";
        }
        $stmt->close();
        header("Location: admin_index.php");
        exit();
    }
}
handleAction('approve', 'testimonials', $con);
handleAction('delete_contact', 'contact_messages', $con);
handleAction('delete_testimonial', 'testimonials', $con);
handleAction('delete_account', 'accounts', $con);

$result = $con->query("SELECT * FROM home_images ORDER BY uploaded_at DESC");
$pending = $con->query("SELECT t.id, a.name, a.email, t.testimonial, t.created_at 
                        FROM testimonials t 
                        JOIN accounts a ON t.user_id = a.id 
                        WHERE t.status = 'pending'
                        ORDER BY t.created_at DESC");
$approved = $con->query("SELECT t.id, a.name, a.email, t.testimonial, t.created_at 
                         FROM testimonials t 
                         JOIN accounts a ON t.user_id = a.id 
                         WHERE t.status = 'approved'
                         ORDER BY t.created_at DESC");
$accounts = $con->query("SELECT id, name, email, created_at FROM accounts ORDER BY created_at DESC");
$contacts = $con->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Testimonials & Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h2>Admin Dashboard</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <div class="container my-4">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registerModal">
            + Register New Admin
        </button>
    </div>

    <form method="POST" action="">
        <button type="submit" name="logout" class="btn btn-danger">Logout</button>
    </form>

    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="registerModalLabel">Register New Admin</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <?= $reg_message ?>
            <form method="POST" action="">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
              </div>
              <button type="submit" name="register_admin" class="btn btn-primary w-100">Register</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <h3 class="mt-4">Pending Testimonials</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-warning">
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Email</th>
                <th>Testimonial</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $pending->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['testimonial']); ?></td>
                <td><?= $row['created_at']; ?></td>
                <td>
                    <a href="admin_index.php?action=approve&id=<?= $row['id']; ?>" 
                       class="btn btn-sm btn-success"
                       onclick="return confirm('Approve this testimonial?');">Approve</a>
                    <a href="admin_index.php?action=delete_testimonial&id=<?= $row['id']; ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this testimonial?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h3 class="mt-5">Approved Testimonials</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-success">
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Email</th>
                <th>Testimonial</th>
                <th>Approved At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $approved->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['testimonial']); ?></td>
                <td><?= $row['created_at']; ?></td>
                <td>
                    <a href="admin_index.php?action=delete_testimonial&id=<?= $row['id']; ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this testimonial?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h3 class="mt-5">Accounts</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($acc = $accounts->fetch_assoc()): ?>
            <tr>
                <td><?= $acc['id']; ?></td>
                <td><?= htmlspecialchars($acc['name']); ?></td>
                <td><?= htmlspecialchars($acc['email']); ?></td>
                <td><?= $acc['created_at']; ?></td>
                <td>
                    <a href="admin_index.php?action=delete_account&id=<?= $acc['id']; ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('WARNING: This will permanently delete the account and their testimonials. Continue?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h3 class="mt-5">Contact Messages</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-info">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Newsletter</th>
                <th>Submitted At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($c = $contacts->fetch_assoc()): ?>
            <tr>
                <td><?= $c['id']; ?></td>
                <td><?= htmlspecialchars($c['first_name'] . " " . $c['last_name']); ?></td>
                <td><?= htmlspecialchars($c['email']); ?></td>
                <td><?= htmlspecialchars($c['phone']); ?></td>
                <td><?= htmlspecialchars($c['subject']); ?></td>
                <td><?= nl2br(htmlspecialchars($c['message'])); ?></td>
                <td><?= $c['newsletter'] ? "Yes" : "No"; ?></td>
                <td><?= $c['submitted_at']; ?></td>
                <td class="d-flex gap-2">
                    <button type="button"
                            class="btn btn-sm btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#replyModal"
                            data-id="<?= $c['id']; ?>"
                            data-name="<?= htmlspecialchars($c['first_name'].' '.$c['last_name'], ENT_QUOTES); ?>"
                            data-email="<?= htmlspecialchars($c['email'], ENT_QUOTES); ?>"
                            data-subject="<?= htmlspecialchars($c['subject'], ENT_QUOTES); ?>">
                        Reply
                    </button>
                    <a href="admin_index.php?action=delete_contact&id=<?= $c['id']; ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this contact message?');">
                       Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <form id="replyForm" method="POST" action="send_reply.php">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="replyModalLabel">Reply to Contact Message</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="contact_id" id="contact_id">
              <input type="hidden" name="to_email" id="to_email">
              <input type="hidden" name="subject" id="subject">
              <div class="mb-3">
                <label for="to_name" class="form-label">To</label>
                <input type="text" class="form-control" id="to_name" readonly>
              </div>
              <div class="mb-3">
                <label for="email_subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="email_subject" readonly>
              </div>
              <div class="mb-3">
                <label for="reply_message" class="form-label">Your Message</label>
                <textarea class="form-control" id="reply_message" name="reply_message" rows="5" required></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Send Reply</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="container mt-5">
        <h2>Upload Home Image</h2>
        <form action="upload_home_image.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="file" name="home_image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload Image</button>
        </form>
    </div>

    <h2>Uploaded Images</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Preview</th>
                <th>File Path</th>
                <th>Uploaded At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $con->query("SELECT * FROM home_images ORDER BY uploaded_at DESC");
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Image" style="width:150px;"></td>
                    <td><?= htmlspecialchars($row['image_path']) ?></td>
                    <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
                    <td>
                        <a href="admin_index.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this image?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="container mt-5">
        <h2>Upload Benefits Image</h2>
        <form action="upload_benefits.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="file" name="benefits_image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload Image</button>
        </form>
    </div>

    <h2>Uploaded Benefits Images</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Preview</th>
                <th>File Path</th>
                <th>Uploaded At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $benefitsResult = $con->query("SELECT * FROM benefits_images ORDER BY uploaded_at DESC");
            while ($row = $benefitsResult->fetch_assoc()):
            ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Image" style="width:150px;"></td>
                    <td><?= htmlspecialchars($row['image_path']) ?></td>
                    <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
                    <td>
                      <a href="admin_index.php?delete_benefits_id=<?= $row['id'] ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this benefits image?')">
                        Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="container mt-5">
        <h2>Upload Download Image</h2>
        <form action="upload_download_image.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="file" name="download_image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload Image</button>
        </form>
    </div>

    <h2>Uploaded Download Images</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Preview</th>
                <th>File Path</th>
                <th>Uploaded At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $con->query("SELECT * FROM download_images ORDER BY uploaded_at DESC");
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Image" style="width:200px;"></td>
                    <td><?= htmlspecialchars($row['image_path']) ?></td>
                    <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
                    <td>
                        <a href="admin_index.php?delete_download_id=<?= $row['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this download image?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="container mt-5">
        <h2>Upload Why Aniko Image</h2>
        <form action="upload_why_aniko.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="file" name="why_aniko_image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload Image</button>
        </form>
    </div>

    <h2>Uploaded Why Aniko Images</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Preview</th>
                <th>File Path</th>
                <th>Uploaded At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $con->query("SELECT * FROM why_aniko_images ORDER BY uploaded_at DESC");
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Why Aniko Image" style="width:200px;"></td>
                    <td><?= htmlspecialchars($row['image_path']) ?></td>
                    <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
                    <td>
                        <a href="admin_index.php?delete_why_aniko_id=<?= $row['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this image?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="container mt-5">
        <h2>Manage Team Members</h2>
        <form action="upload_team_member.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Role / Subtext:</label>
                <input type="text" name="role" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Photo:</label>
                <input type="file" name="team_image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Team Member</button>
        </form>
    </div>

    <h2 class="mt-5">Current Team Members</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $con->query("SELECT * FROM team_members ORDER BY uploaded_at DESC");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Team" style="width:80px; border-radius:50%;"></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                    <a href="admin_index.php?delete_team_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this team member?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const replyModalEl = document.getElementById('replyModal');
replyModalEl.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const email = button.getAttribute('data-email');
    const subject = button.getAttribute('data-subject');

    document.getElementById('contact_id').value = id;
    document.getElementById('to_email').value = email;
    document.getElementById('subject').value = "Re: " + subject;

    document.getElementById('to_name').value = name + " <" + email + ">";
    document.getElementById('email_subject').value = "Re: " + subject;
});
</script>
</html>
