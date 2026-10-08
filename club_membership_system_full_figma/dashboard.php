<?php
session_start();
require_once "config.php";
if (!isset($_SESSION["user_id"])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>ClubHub Dashboard</title><link rel="stylesheet" href="css/style.css">
</head><body class="portal-page member-dashboard-page">
<div class="portal-shell">
<header class="portal-header"><div class="brand-mark">🎨 ♫ 🎭 <strong>CLUBHUB</strong></div><div class="user-welcome">☻ Hi, <?php echo htmlspecialchars($_SESSION["fullname"]); ?>!</div></header>
<aside class="sidebar"><nav>
<a href="dashboard.php" class="active">⌂ <span>Home</span></a><a href="#clubs">♟ <span>My Clubs</span></a><a href="#events">▦ <span>Events</span></a><a href="#announcements">▤ <span>Announcements</span></a><a href="#notifications">♧ <span>Notifications</span></a><a href="#profile">♙ <span>Profile</span></a><a href="#settings">⚙ <span>Settings</span></a><a href="logout.php">⇥ <span>Logout</span></a>
</nav></aside>
<main class="portal-main member-main">
<section class="welcome-banner"><div><h1>Welcome to<br>ClubHub!</h1><p>Discover · Join · Create</p><small>Your creative journey starts here.</small></div><div class="welcome-art">🎨</div><a href="#clubs">Go to My Page →</a></section>
<section class="stat-grid member-stats"><div class="stat-card"><span>♟</span><strong>3</strong><small>Active Clubs</small></div><div class="stat-card"><span>▦</span><strong>12</strong><small>Upcoming Events</small></div><div class="stat-card"><span>🏆</span><strong>5</strong><small>Club Achievements</small></div></section>
<section class="member-panels">
<div class="portal-panel featured-panel" id="clubs"><h2>Featured Clubs</h2><div class="club-cards"><article><div class="club-emoji">🎨</div><strong>Art Club</strong><small>Creative Expression</small></article><article><div class="club-emoji">🎭</div><strong>Theater Club</strong><small>Act · Perform · Shine</small></article><article><div class="club-emoji">♫</div><strong>Music Club</strong><small>Make Beautiful Music</small></article></div></div>
<div class="portal-panel scheduled-panel" id="events"><h2>Scheduled Events</h2><div class="event-row">🎨 <span>Art Workshop<small>Friday, 2:00 PM</small></span> ›</div><div class="event-row">🎭 <span>Theater Audition<small>Saturday, 10:00 AM</small></span> ›</div><div class="event-row">♫ <span>Music Jam Session<small>Sunday, 3:00 PM</small></span> ›</div></div>
</section>
</main></div></body></html>
