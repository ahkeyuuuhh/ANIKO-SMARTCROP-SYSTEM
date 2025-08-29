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
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- ✅ Important -->
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>


    <style>
        :root {
            --primary-green: #1D492C;
            --accent-green: #84cc16;
            --pastel-green: #BDE08A;
            --light-green: #f0fdf4;
            --dark-green: #143820;
            --dark-gray: #374151;
            --light-gray: #f9fafb;
            --white: #ffffff;
            --bg-color: #cfc4b2ff;
            --primary-brown: #8A6440;
            --dark-brown: #4D2D18;
            --gradient-primary: linear-gradient(135deg, var(--primary-green), var(--accent-green));
            --gradient-earthy: linear-gradient(135deg, var(--primary-brown), var(--primary-green));
        }

        body {
            background: var(--gradient-primary) !important;
            height: 100vh;
        }

        .dashboard {
            margin-left: 280px;
            padding: 20px;
            transition: margin-left 0.3s ease;
            margin-top: 4rem !important;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0px 3px 8px rgba(0,0,0,0.15);
        }
        @media (max-width: 992px) {
            .dashboard {
                margin-left: 0;
                padding: 15px;
            }
        }

        .dashboard h1 {
            font-weight: bold;
            color: var(--light-green);
            margin-bottom: 3rem !important;
            text-shadow: 0px 0px 10px var(--accent-green);
        }

        .count-card {
            background: rgba(0, 50, 0, 0.5) !important;
            backdrop-filter: blur(10px) brightness(0.8); 
            -webkit-backdrop-filter: blur(10px) brightness(0.8); 
            border: none !important;
            box-shadow: 0px 0px 20px 2px var(--pastel-green);
        }

        
        .table-card {
            background: rgba(20, 56, 32, 0.55); 
            backdrop-filter: blur(12px) brightness(0.9);
            -webkit-backdrop-filter: blur(12px) brightness(0.9);
            border-radius: 20px !important;
            overflow: hidden; 
            box-shadow: 0 4px 25px rgba(0,0,0,0.35);
            border: none !important;
        }

        h4 {
            color: var(--light-green);
            margin-bottom: 1rem;
        }

        .users-btn {
            background-color: var(--primary-brown);
            padding: 5px 20px;
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-weight: 500;
            align-items: center; 
        }

        .table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 20px;
            overflow: hidden;
            border: none !important;
        }

        .table thead th {
            background-color: var(--pastel-green);
            color: var(--primary-green);
            text-align: center;
            font-weight: 700 !important;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            border: none !important; 
        }

        .table tbody tr {
            background: rgba(72, 56, 56, 0.28) !important;
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(180, 255, 180, 0.15);
            transform: scale(1.01);
            box-shadow: inset 0px 0px 8px rgba(132, 204, 22, 0.4);
        }

        .table td {
            color: var(--primary-brown);
            text-align: center;
            padding: 12px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .table td:first-child {
            font-weight: bold;
            color: var(--dark-brown);
        }

        .table tbody tr td[colspan] {
            font-style: italic;
            color: var(--pastel-green);
            text-shadow: 0 0 6px rgba(255,255,255,0.2);
        }

        .display-6 {
            font-weight: 700;
            color: var(--light-green);
        }
        
        .bi {
            margin-right: 1rem;
        }
          .dashboard {
        margin-left: 280px;
        padding: 20px;
        transition: margin-left 0.3s ease;
        margin-top: 4rem !important;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0px 3px 8px rgba(0,0,0,0.15);
    }

    .dashboard h1 {
        font-weight: bold;
        color: var(--light-green);
        margin-bottom: 3rem !important;
        text-shadow: 0px 0px 10px var(--accent-green);
    }

    .count-card {
        background: rgba(0, 50, 0, 0.5) !important;
        backdrop-filter: blur(10px) brightness(0.8);
        border: none !important;
        box-shadow: 0px 0px 20px 2px var(--pastel-green);
    }

    .display-6 {
        font-weight: 700;
        color: var(--light-green);
    }
    
.card canvas {
    max-height: 280px;   
    width: 100% !important; 
    margin: auto;
}


@media (max-width: 768px) {
    .card canvas {
        max-height: 200px;
    }
}

    </style>
</head>
<body>
   <div class="dashboard">
  <h1 class="mb-4"> Admin Dashboard</h1>

<!-- Quick Stats Section -->
<div id="statsSection" class="row g-4 sortable-section">
    <div class="col-sm-6 col-lg-4">
        <div class="card text-center count-card">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <p class="display-6"><?= $users; ?></p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card text-center count-card">
            <div class="card-body">
                <h5 class="card-title">Admin Accounts</h5>
                <p class="display-6"><?= $admins; ?></p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card text-center count-card">
            <div class="card-body">
                <h5 class="card-title">Downloads</h5>
                <p class="display-6">Coming Soon</p>
            </div>
        </div>
    </div>
</div>

 <!-- Graphs Section -->
<div id="graphsSection" class="row mt-5 g-4 sortable-section">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5>Registrations (Last 7 Days)</h5>
                <canvas id="usersChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5>Accounts Overview</h5>
                <canvas id="rolesChart"></canvas>
            </div>
        </div>
    </div>
</div>
  <!-- Testimonials & Contact Messages Section -->
 <div id="tablesSection" class="row mt-5 g-4 sortable-section">
    <!-- Testimonials (Left) -->
    <div class="col-lg-6">
      <div class="card table-card">
        <div class="card-body">
          <h5>Testimonials</h5>
          <table class="table table-bordered table-striped">
            <thead class="table-warning">
              <tr>
                <th>Email</th>
                <th>Testimonial</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($pending->num_rows > 0): ?>
                <?php while ($row = $pending->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['testimonial']); ?></td>
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
              <?php else: ?>
                <tr>
                  <td colspan="3" class="text-center text-muted">No pending testimonials found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Contact Messages (Right) -->
    <div class="col-lg-6">
      <div class="card table-card">
        <div class="card-body">
          <h5>Contact Messages</h5>

          <?php if (isset($_SESSION['message'])): ?>
              <div class="alert alert-info">
                  <?= $_SESSION['message']; unset($_SESSION['message']); ?>
              </div>
          <?php endif; ?>

          <table class="table table-bordered table-striped">
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
  </div>




</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Line chart for users growth
const ctx1 = document.getElementById('usersChart').getContext('2d');
new Chart(ctx1, {
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
    }
});

// Pie chart for roles
const ctx2 = document.getElementById('rolesChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Users', 'Admins'],
        datasets: [{
            data: [<?= $users; ?>, <?= $admins; ?>],
            backgroundColor: ['#1D492C', '#8A6440']
        }]
    },
    options: { responsive: true }
});


</script>
<script>
  // Apply Sortable to every section
  document.querySelectorAll('.sortable-section').forEach(section => {
    new Sortable(section, {
      animation: 150,
      ghostClass: 'dragging', // Adds a style when dragging
      handle: '.card', // Entire card is draggable
      swap: true
    });
  });
</script>

<style>
  /* Optional styling when dragging */
  .dragging {
    opacity: 0.9;
    transform: scale(1.02);
  }
</style>

</body>
</html>
