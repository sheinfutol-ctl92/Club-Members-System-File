<?php
session_start();
require_once "config.php";
if (!isset($_SESSION["admin_id"])) { header("Location: admin_login.php"); exit(); }

$memberCount = 0; $eventCount = 12; $clubCount = 3; $announcementCount = 4;
$q = $conn->query("SELECT COUNT(*) AS total FROM users");
if ($q) $memberCount = (int)$q->fetch_assoc()["total"];
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard</title><link rel="stylesheet" href="css/style.css">
</head><body class="portal-page admin-dashboard-page">
<div class="portal-shell">
<header class="portal-header"><div class="brand-mark">🎨 ♫ 🎭 <strong>Admin</strong></div><a href="admin_logout.php" class="header-logout">Logout</a></header>
<aside class="sidebar"><nav>
<a href="admin_dashboard.php" class="active">⌂ <span>Home</span></a>
<a href="#clubs">♟ <span>My Clubs</span></a><a href="#events">▦ <span>Events</span></a>
<a href="#announcements">▤ <span>Announcements</span></a><a href="#notifications">♧ <span>Notifications</span></a>
<a href="#profile">♙ <span>Profile</span></a><a href="#settings">⚙ <span>Settings</span></a>
</nav></aside>
<main class="portal-main">
<div class="greeting"><h1>Good morning, Admin! ✦</h1><p>Here's what's happening in ClubHub today.</p></div>
<section class="stat-grid">
<div class="stat-card"><span>👥</span><strong><?php echo $memberCount; ?></strong><small>Members</small></div>
<div class="stat-card"><span>🎨</span><strong><?php echo $clubCount; ?></strong><small>3 CLUBS</small></div>
<div class="stat-card"><span>▦</span><strong><?php echo $eventCount; ?></strong><small>12 EVENTS</small></div>
<div class="stat-card"><span>📣</span><strong><?php echo $announcementCount; ?></strong><small>ANNOUNCEMENTS</small></div>
</section>
<section class="admin-panels">
<div class="portal-panel activity-panel"><h2>◷ RECENT ACTIVITY</h2>
<ul><li><b>👤</b> New member joined Art Club... <small>2m ago</small></li><li><b>♫</b> Music Jam Session created... <small>1h ago</small></li><li><b>▤</b> New announcement posted... <small>3h ago</small></li><li><b>🎭</b> Theater auditions updated... <small>5h ago</small></li></ul></div>
<div class="portal-panel club-overview" id="clubs"><h2>🎨 CLUB OVERVIEW</h2><p>🎨 Art <strong><?php echo max(32, $memberCount); ?></strong></p><p>🎭 Theater <strong>19</strong></p><p>♫ Music <strong>22</strong></p></div>
</section>
</main></div></body></html>
