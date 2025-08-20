<!-- Main Content -->
    <header class="border border-b border-gray-100 bg-white px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <img src="https://i.pravatar.cc/40" alt="profile" class="w-8 h-8 rounded-full">
                <span class="text-sm font-medium">
                    <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>
                </span>
            </div>
        </div>
    </header>
