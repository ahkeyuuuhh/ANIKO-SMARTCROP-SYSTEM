<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'CONFIG/config.php';
include 'INCLUDE/admin_header.php';

// ✅ Only allow logged-in admins
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// --- Get Counts ---
$res = $con->query("SELECT COUNT(*) AS total FROM accounts");
$users = $res->fetch_assoc()['total'] ?? 0;

$res = $con->query("SELECT COUNT(*) AS total FROM admin_accounts");
$admins = $res->fetch_assoc()['total'] ?? 0;

// --- Recent registrations for chart (last 7 days) ---
$registrations = [];
$labels = [];
for ($i = 6; $i >= 0; $i--) {
    $day = date("Y-m-d", strtotime("-$i days"));
    $labels[] = date("M d", strtotime($day));
    $stmt = $con->prepare("SELECT COUNT(*) as total FROM accounts WHERE DATE(created_at) = ?");
    $stmt->bind_param("s", $day);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $registrations[] = $result['total'] ?? 0;
}

// ✅ Function to handle testimonial actions
function handleAction($action, $con) {
    if (isset($_GET['action']) && $_GET['action'] === $action && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if ($action === 'approve') {
            $stmt = $con->prepare("UPDATE testimonials SET status = 'approved' WHERE id = ?");
        } elseif ($action === 'delete_testimonial') {
            $stmt = $con->prepare("DELETE FROM testimonials WHERE id = ?");
        }
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = ucfirst($action) . "d successfully!";
        } else {
            $_SESSION['message'] = "Error " . $action . "ing.";
        }
        $stmt->close();
        header("Location: admin_testimonial.php");
        exit();
    }
}
handleAction('approve', $con);
handleAction('delete_testimonial', $con);

// ✅ Fetch testimonials
$pending = $con->query("
    SELECT t.id, a.name, a.email, t.testimonial, t.created_at 
    FROM testimonials t 
    JOIN accounts a ON t.user_id = a.id 
    WHERE t.status = 'pending'
    ORDER BY t.created_at DESC
");

$approved = $con->query("
    SELECT t.id, a.name, a.email, t.testimonial, t.created_at 
    FROM testimonials t 
    JOIN accounts a ON t.user_id = a.id 
    WHERE t.status = 'approved'
    ORDER BY t.created_at DESC
");

// Handle delete action (contacts)
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

// Fetch contact messages
$contacts = $con->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-green: #1D492C;
            --accent-green: #84cc16;
            --pastel-green: #BDE08A;
            --light-green: #f0fdf4;
            --dark-green: #143820;
            --primary-brown: #8A6440;
            --dark-brown: #4D2D18;
            --gradient-primary: linear-gradient(135deg, var(--primary-green), var(--accent-green));
        }
        body {
            background: var(--gradient-primary) !important;
            min-height: 100vh;
        }
        .dashboard {
            margin-left: 280px;
            padding: 20px;
            transition: margin-left 0.3s ease;
            margin-top: 4rem !important;
        }
        @media (max-width: 992px) {
            .dashboard { margin-left: 0; padding: 15px; }
        }
        .dashboard h1 {
            font-weight: bold; color: var(--light-green);
            margin-bottom: 3rem !important; text-shadow: 0 0 10px var(--accent-green);
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.15);
            cursor: grab;
        }
        .count-card {
            background: rgba(0, 50, 0, 0.5) !important;
            backdrop-filter: blur(10px) brightness(0.8);
            border: none !important;
            box-shadow: 0 0 20px 2px var(--pastel-green);
        }
        .table-card {
            background: rgba(20, 56, 32, 0.55);
            backdrop-filter: blur(12px) brightness(0.9);
            border-radius: 20px !important;
            overflow: hidden;
            box-shadow: 0 4px 25px rgba(0,0,0,0.35);
            border: none !important;
        }
        .display-6 { font-weight: 700; color: var(--light-green); }
        .table thead th {
            background-color: var(--pastel-green);
            color: var(--primary-green);
            text-align: center;
            font-weight: 700 !important;
            border: none !important;
        }
        .table tbody tr { background: rgba(72,56,56,0.28) !important; transition: all 0.3s; }
        .table tbody tr:hover {
            background: rgba(180,255,180,0.15);
            transform: scale(1.01);
            box-shadow: inset 0 0 8px rgba(132,204,22,0.4);
        }
        /* Canvas sizing */
        .card canvas {
            max-height: 280px;
            width: 100% !important;
            margin: auto;
        }
        @media (max-width: 768px) {
            .card canvas { max-height: 200px; }
        }

        /* 🔥 Drag styles: near full opacity + on top */
        .dragging, .sortable-chosen {
            z-index: 9999 !important;
        }
        .dragging .card, .sortable-chosen .card {
            opacity: 0.98 !important;
            transform: scale(1.02);
        }
        /* Ghost placeholder looks subtle */
        .sortable-ghost { opacity: 0.3; }

        /* We make the columns the draggable items */
        .draggable-col { will-change: transform; }
         /* Make dragged card nearly solid */
  .sortable-ghost { opacity: 0.4 !important; }
  .sortable-swap-highlight { outline: 3px dashed var(--accent-green); }
  .dragging { opacity: 0.95 !important; transform: scale(1.02); z-index: 9999; }
    </style>
</head>
<body>
<div class="dashboard">
  <h1 class="mb-4">Admin Dashboard</h1>

  <!-- ✅ Quick Stats (draggable columns) -->
  <div id="statsSection" class="row g-4 dashboard-group">
      <div class="col-sm-6 col-lg-4 ">
          <div class="card text-center count-card">
              <div class="card-body">
                  <h5 class="card-title">Total Users</h5>
                  <p class="display-6"><?= $users; ?></p>
              </div>
          </div>
      </div>
      <div class="col-sm-6 col-lg-4 ">
          <div class="card text-center count-card">
              <div class="card-body">
                  <h5 class="card-title">Admin Accounts</h5>
                  <p class="display-6"><?= $admins; ?></p>
              </div>
          </div>
      </div>
      <div class="col-sm-6 col-lg-4 ">
          <div class="card text-center count-card">
              <div class="card-body">
                  <h5 class="card-title">Downloads</h5>
                  <p class="display-6">Coming Soon</p>
              </div>
          </div>
      </div>
  </div>

 <div id="grid" class="row row-cols-1 row-cols-md-2 g-4 mt-5">
  <!-- Graph: Registrations -->
  <div class="col draggable-col">
    <div class="card h-100">
      <div class="card-body">
        <h5>Registrations (Last 7 Days)</h5>
        <canvas id="usersChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Graph: Roles -->
  <div class="col draggable-col">
    <div class="card h-100">
      <div class="card-body">
        <h5>Accounts Overview</h5>
        <canvas id="rolesChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Testimonials -->
  <div class="col draggable-col">
    <div class="card table-card h-100">
      <div class="card-body">
        <h5>Testimonials</h5>
        <table class="table table-bordered table-striped mt-3">
          <thead class="table-info">
            <tr>
              <th>Email</th>
              <th>Testimonial</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php if ($pending && $pending->num_rows > 0): ?>
            <?php while ($row = $pending->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['testimonial']); ?></td>
                <td class="d-flex gap-2">
                  <a href="admin_index.php?action=approve&id=<?= $row['id']; ?>" 
                     class="btn btn-sm btn-success"
                     onclick="return confirm('Approve this testimonial?');">Approve</a>
                  <a href="admin_index.php?action=delete_testimonial&id=<?= $row['id']; ?>" 
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('Delete this testimonial?');">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="3" class="text-center text-muted">No pending testimonials found.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Contacts -->
  <div class="col draggable-col">
    <div class="card table-card h-100">
      <div class="card-body">
        <h5>Contact Messages</h5>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?= $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <table class="table table-bordered table-striped mt-3">
          <thead class="table-info">
            <tr>
              <th>Email</th>
              <th>Message</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php while ($c = $contacts->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($c['email']); ?></td>
              <td><?= nl2br(htmlspecialchars($c['message'])); ?></td>
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
      </div>
    </div>
  </div>
</div>
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
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
<script>
  // Charts
  const usersChartCtx = document.getElementById('usersChart').getContext('2d');
  const usersChart = new Chart(usersChartCtx, {
    type: 'line',
    data: {
      labels: <?= json_encode($labels); ?>,
      datasets: [{
        label: 'Registrations',
        data: <?= json_encode($registrations); ?>,
        borderColor: '#84cc16',
        backgroundColor: 'rgba(132,204,22,0.3)',
        fill: true,
        tension: 0.3
      }]
    },
    options: { maintainAspectRatio: false }
  });

  const rolesChartCtx = document.getElementById('rolesChart').getContext('2d');
  const rolesChart = new Chart(rolesChartCtx, {
    type: 'doughnut',
    data: {
      labels: ['Users', 'Admins'],
      datasets: [{
        data: [<?= $users; ?>, <?= $admins; ?>],
        backgroundColor: ['#1D492C', '#8A6440']
      }]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });

  // ✅ Make ALL rows cross-draggable by giving them the same Sortable group
  const groupOptions = {
    group: { name: 'dashboardGroup', pull: true, put: true },
    animation: 180,
    ghostClass: 'sortable-ghost',
    chosenClass: 'dragging',
    dragClass: 'dragging',
    handle: '.card',
    draggable: '.draggable-col',
    onEnd: () => {
      // Resize charts after DOM move to ensure proper fit
      try { usersChart.resize(); } catch(e) {}
      try { rolesChart.resize(); } catch(e) {}
    }
  };
  ['statsSection', 'graphsSection', 'tablesSection'].forEach(id => {
    new Sortable(document.getElementById(id), groupOptions);
  });

  // Modal populate
  const replyModal = document.getElementById('replyModal');
  replyModal?.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    if (!button) return;
    document.getElementById('contact_id').value  = button.getAttribute('data-id') || '';
    document.getElementById('to_email').value    = button.getAttribute('data-email') || '';
    document.getElementById('subject').value     = button.getAttribute('data-subject') || '';
    document.getElementById('to_name').value     = button.getAttribute('data-name') || '';
    document.getElementById('email_subject').value = button.getAttribute('data-subject') || '';
  });
</script>
<script>
  // Example charts
  new Chart(document.getElementById("usersChart"), {
    type: "line",
    data: {
      labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      datasets: [{ label: "Users", data: [12, 19, 3, 5, 2, 3, 9] }]
    }
  });

  new Chart(document.getElementById("rolesChart"), {
    type: "doughnut",
    data: {
      labels: ["Users", "Admins"],
      datasets: [{ data: [90, 10] }]
    }
  });

  // Enable swapping only
  new Sortable(document.getElementById("grid"), {
    animation: 150,
    swap: true,
    swapClass: "dragging",
    ghostClass: "dragging",
    forceFallback: true
  });
</script>
<script>
  new Sortable(document.getElementById("grid"), {
    animation: 200,
    swap: true,
    swapClass: "sortable-swap-highlight",
    ghostClass: "sortable-ghost",
    draggable: ".draggable-col",
    forceFallback: true,
    onEnd: () => {
      try { usersChart.resize(); } catch(e) {}
      try { rolesChart.resize(); } catch(e) {}
    }
  });
</script>
</body>
</html>
