
	<footer class="footer">
		<div class="footer-container-fluid">
			<p class="text-muted">
				&copy; 2016 {{ config('application.title') . ' v' . config('application.version') }} |
				Timezone: {{ \DateTimeHelper::get_timezone_by_offset(\DateTimeHelper::get_timezone()) }} ({{ \DateTimeHelper::get_timezone() }}) |
				Logged-in: {{ session('user.firstname') . ' ' . session('user.lastname') }} ({{ \Redis::hget("role_names", session('user.role_id')) }}) |
				<a target="_blank" href="http://nmsloop.com/report/create/eyJuYW1lIjoiY2FyeWwiLCJ1cmwiOiJwYW5lbFwvcGFyayIsInBsYXRmb3JtIjoiaW0iLCJoYXNoIjoiNTEzYWFkIn0=">
				report a problem
				</a>
			</p>
		</div>
	</footer>