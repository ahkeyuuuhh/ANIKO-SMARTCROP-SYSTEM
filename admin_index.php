<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'CONFIG/config.php'; // DB connection

// --- Handle Approve ---
if (isset($_GET['action']) && $_GET['action'] === 'approve' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $con->prepare("UPDATE testimonials SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Testimonial approved successfully!";
    } else {
        $_SESSION['message'] = "Error approving testimonial.";
    }
    $stmt->close();
    header("Location: admin_index.php");
    exit();
}

// --- Handle Delete Contact Message ---
if (isset($_GET['action']) && $_GET['action'] === 'delete_contact' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $con->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Contact message deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting contact message.";
    }
    $stmt->close();
    header("Location: admin_index.php");
    exit();
}

// --- Handle Delete Testimonial ---
if (isset($_GET['action']) && $_GET['action'] === 'delete_testimonial' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $con->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Testimonial deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting testimonial.";
    }
    $stmt->close();
    header("Location: admin_index.php");
    exit();
}

// --- Handle Delete Account ---
if (isset($_GET['action']) && $_GET['action'] === 'delete_account' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $con->prepare("DELETE FROM accounts WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Account deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting account.";
    }
    $stmt->close();
    header("Location: admin_index.php");
    exit();
}

// --- Fetch Testimonials ---
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

// --- Fetch Accounts ---
$accounts = $con->query("SELECT id, name, email, created_at FROM accounts ORDER BY created_at DESC");

// --- Fetch Contact Messages ---
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

    <!-- Pending Testimonials -->
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

    <!-- Approved Testimonials -->
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

    <!-- Accounts Table -->
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
                       onclick="return confirm('⚠️ WARNING: This will permanently delete the account and their testimonials. Continue?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Contact Messages Table -->
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
                <td><?= $c['newsletter'] ? "✅ Yes" : "❌ No"; ?></td>
                <td><?= $c['submitted_at']; ?></td>
                <td class="d-flex gap-2">
                    <!-- Reply button uses data-* attributes to safely pass values -->
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

    <!-- Reply Modal -->
    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <form id="replyForm" method="POST" action="send_reply.php">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="replyModalLabel">Reply to Contact Message</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <!-- Hidden fields -->
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

</body>
<!-- ✅ Load Bootstrap JS bundle (required for modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Fill modal inputs using Bootstrap's relatedTarget
const replyModalEl = document.getElementById('replyModal');
replyModalEl.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget; // Button that triggered the modal
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
