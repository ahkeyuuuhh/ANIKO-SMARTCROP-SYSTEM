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

$res = $con->query("SELECT COUNT(*) AS total FROM accounts");
$users = $res->fetch_assoc()['total'] ?? 0;

$res = $con->query("SELECT COUNT(*) AS total FROM admin_accounts");
$admins = $res->fetch_assoc()['total'] ?? 0;

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
            --gradient-secondary: linear-gradient(135deg, var(--primary-green), var(--pastel-green));
        }

        body {
          background-color: var(--bg-color) !important;
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
          font-weight: bold; 
          color: var(--dark-brown);
          text-align: center;
        }

        .subheader {
          text-align: center;
          font-size: 18px; 
          font-weight: 400;
          color: var(--primary-brown);
          margin-bottom: 3rem;
        }

        .card {
          border-radius: 15px;
          box-shadow: 0 3px 8px rgba(0,0,0,0.15);
          cursor: grab;
        }

        .card-title {
          color:var(--pastel-green) !important;
        }

        .count-card {
          background: var(--gradient-secondary) !important;
          backdrop-filter: blur(10px) brightness(0.8);
          border: 2px solid var(--dark-green) !important;
          border-top-right-radius: 100px !important;
          border-bottom-left-radius: 100px !important;
          border-top-left-radius: 0 !important;
          border-bottom-right-radius: 0 !important;

        }

        .table-card {
          background-color: var(--pastel-green);
          backdrop-filter: blur(12px) brightness(0.9);
          border-radius: 20px !important;
          overflow: hidden;
          box-shadow: 0 4px 25px rgba(0,0,0,0.35);
          padding: 20px;
          border: 2px solid var(--primary-brown);
        }

        .display-6 { 
          font-weight: 700; color: var(--light-green); 
          text-shadow: 0px 0px 10px var(--accent-green);
        }

        .table thead th {
          background-color: var(--pastel-green);
          color: var(--primary-green);
          text-align: center;
          font-weight: 700 !important;
          border: none !important;
        }

        .table tbody tr { background: rgba(72,56,56,0.28) !important; transition: all 0.3s; }
        .table tbody tr:hover {
            transform: scale(1.01);
            box-shadow: inset 0 0 8px rgba(132,204,22,0.4);
        }

        .card canvas {
            max-height: 300px;
            width: 100% !important;
            margin: auto;
        }

        @media (max-width: 768px) {
            .card canvas { max-height: 200px; }
        }

        .dragging, .sortable-chosen {
            z-index: 9999 !important;
        }

        .dragging .card, .sortable-chosen .card {
            opacity: 0.98 !important;
            transform: scale(1.02);
        }

        .sortable-ghost { 
          opacity: 0.3; 
        }

        .draggable-col { 
          will-change: transform; 
        }

        .sortable-ghost { 
          opacity: 0.4 !important; 
        }

        .sortable-swap-highlight { 
          outline: 3px dashed var(--accent-green); 
        }

        .dragging { 
          opacity: 0.95 !important; 
          transform: scale(1.02); 
          z-index: 9999; 
        }

        .regDash-card {
          background-color: var(--light-green) !important;
          border: 2px solid var(--primary-green);
        }

        .regDash-card h5 {
          color: var(--primary-green);
          font-weight: bold;
        }

        .accDash-card h5 {
          color: var(--light-green) !important;
        }

        .accDash-card {
          background: var(--gradient-secondary) !important;
          backdrop-filter: blur(12px) brightness(0.9);
          -webkit-backdrop-filter: blur(12px) brightness(0.9);
          color: var(--pastel-green);
          border:2px solid var(--dark-green);
        }

        .accDash-card-body {
          border: none !important;
          color: var(--light-green) !important;
        }

        .bi {
          margin-right: 1rem;
        }

          .table {
            border-radius: 20px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.15); 
            color: var(--white);
            border:none !important;
            border-collapse: collapse !important;
            width: 100%;
        }

        .table th {
            text-align: center;
            font-weight: 600;
            padding: 12px;
            background: rgba(0, 0, 0, 0.25) !important; 
            color: var(--light-green);
        }

        .table td {
            vertical-align: middle;
            padding: 10px;
            color: var(--primary-green) !important;
        }

        table td, 
        table th {
            border: 1px solid #ccc; 
        }

        table tr:first-child th {
            border-top: none; 
        }

        table tr:last-child td {
            border-bottom: none; 
        }

        table td:first-child,
        table th:first-child {
            border-left: none; 
        }

        table td:last-child,
        table th:last-child {
            border-right: none; 
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            transition: 0.3s ease;
        }

        .table thead tr  {
            background-color: var(--pastel-green);
            border-top: none!important;
            color: var(--dark-green) !important;
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
        }

        .table .btn {
            border-radius: 20px;
            font-size: 0.85rem;
            padding: 4px 10px;
            transition: all 0.3s ease;
        }

        .table .btn-success:hover {
            background-color: var(--dark-green) !important;
            color: var(--white);
        }

        .table .btn-danger {
            background: #dc2626;
            border: none;
        }

        .table .btn-danger:hover {
            background: #b91c1c;
        }

        .bi-trash3-fill {
            margin-right: 7px;
        }

        .bi-trash-con, .bi-reply-fill{
          margin: 0 !important;
        }

        .reply-btn, .approve-btn {
          background-color: var(--pastel-green);
          color: var(--primary-green);
          border: none !important;
          font-weight: 500;
        }

        .reply-btn:hover {
          background-color: var(--primary-green);
          color: var(--light-green);
        }

        .approve-btn:hover {
          background-color: var(--primary-green);
          color: var(--light-green);
        }

        .heading {
          display: flex;
        }

        .heading h5{
          color: var(--primary-green);
          font-weight: bold;
        }

        .heading a {
          text: end;
          margin-left: auto;
          background-color: var(--primary-brown);
          color: var(--light-green);
          font-weight: 500;
          border: none !important;
          border-radius: 15px;
          padding: 5px 20px;
        }

        .heading a:hover {
          background-color: var(--dark-brown) !important;
        }

        .row .header2 {
          margin-top: 2rem;
          margin-bottom: 1rem;
          color: var(--primary-brown);
          font-weight: bold;
          text-shadow: 0px 2px 3px #00000030;
        }
    </style>
</head>
<body>
  <div class="dashboard">
    <h1 class="header">Admin Dashboard</h1>
    <h6 class="subheader">Streamline Your Operations</h6>

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

    <div class="row">
      <h3 class="header2">Overview</h3>
    </div>

    <div id="grid" class="row row-cols-1 row-cols-md-2 g-4">
      <div class="col draggable-col">
        <div class="card h-100 regDash-card">
          <div class="card-body regDash-card-body">
            <h5><i class="bi bi-clipboard-data-fill"></i>Registrations (Last 7 Days)</h5>
            <canvas id="usersChart"></canvas>
          </div>
        </div>
      </div>

      <div class="col draggable-col">
        <div class="card h-100 accDash-card">
          <div class="card-body addDash-card-body">
            <h5><i class="bi bi-person-lines-fill"></i>Accounts Overview</h5>
            <canvas id="rolesChart"></canvas>
          </div>
        </div>
      </div>

      <div class="col draggable-col">
        <div class="card table-card h-100">
          <div class="card-body">
            <div class="heading">
              <h5 class="d-flex justify-content-between align-items-center">
                <i class="bi bi-chat-left-dots-fill"></i>Testimonials</h5>
              <a href="admin_testimonial.php" class="btn btn-primary btn-sm">View More</a>
            </div>
           

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
                      <!-- Approve Button -->
                      <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal<?= $row['id']; ?>">
                        <i class="bi bi-check"></i> Approve
                      </button>

                      <!-- Delete Button -->
                      <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['id']; ?>">
                        <i class="bi bi-trash3-fill"></i> Delete
                      </button>
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

            <div class="heading">
                <h5 class="d-flex justify-content-between align-items-center">
                  <i class="bi bi-chat-square-text-fill"></i>Contact Messages            </h5>
                <a href="admin_contact.php" class="btn btn-primary btn-sm">View More</a>
            </div>

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
                   <td>
                      <div class="d-flex gap-2 justify-content-center w-100">
                          <button type="button"
                                  class="btn btn-sm btn-primary reply-btn"
                                  data-bs-toggle="modal"
                                  data-bs-target="#replyModal"
                                  data-id="<?= $c['id']; ?>"
                                  data-name="<?= htmlspecialchars($c['first_name'].' '.$c['last_name'], ENT_QUOTES); ?>"
                                  data-email="<?= htmlspecialchars($c['email'], ENT_QUOTES); ?>"
                                  data-subject="<?= htmlspecialchars($c['subject'], ENT_QUOTES); ?>">
                              <i class="bi bi-reply-fill"></i>Reply
                          </button>
                          <a href="admin_contact.php?action=delete_contact&id=<?= $c['id']; ?>" 
                            class="btn btn-sm btn-danger del-btn"
                            onclick="return confirm('Delete this contact message?');">
                            <i class="bi bi-trash3-fill bi-trash-con"></i>Delete
                          </a>
                      </div>
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

  <!-- Approve Modal -->
<div class="modal fade" id="approveModal<?= $row['id']; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Confirm Approval</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Approve testimonial from <strong><?= htmlspecialchars($row['email']); ?></strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="admin_index.php?action=approve&id=<?= $row['id']; ?>" class="btn btn-success">Yes, Approve</a>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal<?= $row['id']; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Delete testimonial from <strong><?= htmlspecialchars($row['email']); ?></strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="admin_index.php?action=delete_testimonial&id=<?= $row['id']; ?>" class="btn btn-danger">Yes, Delete</a>
      </div>
    </div>
  </div>
</div>

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
          backgroundColor: ['#1D492C', '#84cc16'],
          borderWidth: 0
        }]
      },
      options: { 
        responsive: true, 
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              color: '#fff',
            }
          }
        }
      },
      plugins: [{
        id: 'glow',
        beforeDatasetDraw(chart, args) {
          const {ctx} = chart;
          const dataset = chart.data.datasets[args.index];
          const meta = chart.getDatasetMeta(args.index);

          ctx.save();
          ctx.shadowColor = 'rgba(78, 228, 68, 0.65)'; 
          ctx.shadowBlur = 30;                        
          ctx.shadowOffsetX = 0;
          ctx.shadowOffsetY = 0;

          meta.data.forEach((element, i) => {
            ctx.fillStyle = dataset.backgroundColor[i];
            element.draw(ctx);
          });

          ctx.restore();
          return false; 
        }
      }]
    });

    const groupOptions = {
      group: { name: 'dashboardGroup', pull: true, put: true },
      animation: 180,
      ghostClass: 'sortable-ghost',
      chosenClass: 'dragging',
      dragClass: 'dragging',
      handle: '.card',
      draggable: '.draggable-col',
      onEnd: () => {
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
