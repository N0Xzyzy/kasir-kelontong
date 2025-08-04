<!-- Main Content -->
 <?php
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
?>
    <div class="flex-1 flex flex-col">
      
      <!-- Header -->
      <header class="bg-white border-r border-gray-200 px-6 py-4 flex justify-between items-center">
        <!-- Search -->
        <div class="w-1/2">
          <input type="text" placeholder="Search" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
        </div>

        <!-- Right -->
        <div class="flex items-center space-x-4">
          <div class="flex items-center space-x-2">
            <img src="https://i.pravatar.cc/40" alt="profile" class="w-8 h-8 rounded-full">
            <span class="text-sm font-medium"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
          </div>
        </div>
      </header>
    </div>