<!-- Main Header -->
<header class="main-header">

<!-- Logo -->
<a href="/home" class="logo">
  <!-- mini logo for sidebar mini 50x50 pixels -->
  <span class="logo-mini"><b>A</b>sp</span>
  <!-- logo for regular state and mobile devices -->
  <span class="logo-lg"><b>A S P H A</b></span>
</a>

<!-- Header Navbar -->
<nav class="navbar navbar-static-top" role="navigation">
  <!-- Sidebar toggle button-->
  <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
	<span class="sr-only">Toggle navigation</span>
  </a>
  <!-- Navbar Right Menu -->
  <div class="navbar-custom-menu">
	<ul class="nav navbar-nav">
	 
	  <li class="dropdown user user-menu">
		<!-- Menu Toggle Button -->
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		  <!-- The user image in the navbar-->
		  <img src="{{ URL::to('/img/noimg.jpg') }}" class="user-image" alt="User Image">
		  <!-- hidden-xs hides the username on small devices so only the image appears. -->
		  <span class="hidden-xs">{{ Auth::user()->name }}</span>
		</a>
		<ul class="dropdown-menu">
		  <!-- The user image in the menu -->
		  <li class="user-header">
			<img src="{{ URL::to('/img/noimg.jpg') }}" class="img-circle" alt="User Image">

			<p>
			  {{ Auth::user()->name }} - {{ Auth::user()->access }}
			  <small>Member since {{ Auth::user()->created_at }} </small>
			</p>
		  </li>
		  <!-- Menu Body -->

		  <!-- Menu Footer-->
		  <li class="user-footer">

			<div class="pull-right">
			  <a href="{{ url('/logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Sign out</a>
        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
			</div>
		  </li>
		</ul>
	  </li>
	</ul>
  </div>
</nav>
</header>
<!-- Left side column. contains the logo and sidebar -->