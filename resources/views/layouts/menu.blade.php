<ul class="sidebar-menu">
	<li class="header">Menu</li>
	<!-- Optionally, you can add icons to the links -->

	<!-- Code is preserved for easy development
  <li class="active">
    <a href="#">
      <i class="fa fa-link"></i>
      <span>Link</span>
    </a>
  </li>-->

	<li class="treeview @if ($top_menu_sel=="event") active @endif">
		<a href="#">
			<i class="fa fa-search"></i>
			<span>Monitor</span>
			<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
			</span>
		</a>
		<ul class="treeview-menu">
			<li @if ($top_menu_sel== "event") class="active" @endif>
				<a href="/monitor/live/event">Live Event</a>
			</li>
		</ul>
	</li>
	
	<li class="treeview @if ($top_menu_sel=="controller_view" || $top_menu_sel=="controller_add" ) active @endif">
		<a href="#">
			<i class="fa fa-gear"></i>
			<span>Settings</span>
			<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
			</span>
		</a>
		<ul class="treeview-menu">
			<li class="treeview @if ($top_menu_sel=="controller_view" || $top_menu_sel=="controller_add" || $top_menu_sel=="controllersub_view" ) active @endif">
				<a href="#">
					<span>Setup</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li class="treeview @if ($top_menu_sel=="controller_view" || $top_menu_sel=="controller_add" || $top_menu_sel=="restart") active @endif">
						<a href="#">
							<span>Controllers</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li @if ($top_menu_sel== "controller_view") class="active" @endif>
								<a href="/settings/controller">View Controllers</a>
							</li>
							<li @if ($top_menu_sel== "controller_add") class="active" @endif>
								<a href="/settings/add/controller">Add Controllers</a>
							</li>
							<li class="treeview @if ($top_menu_sel=="restart") active @endif">
								<a href="/settings/setup/restart">Restart</a>
							</li>
						</ul>
					</li>
				</ul>
			</li>
		</ul>
	</li>
</ul>
