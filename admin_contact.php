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

// Handle delete action
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
    header("Location: admin_contact.php");
    exit();
}

// Fetch contact messages
$contacts = $con->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Contact Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h2>Contact Messages</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-striped mt-4">
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
                    <a href="admin_contact.php?action=delete_contact&id=<?= $c['id']; ?>" 
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
</body>
</html>
