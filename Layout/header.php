
    <header class="border border-b border-gray-100 bg-white px-6 py-4 flex justify-between items-center fixed w-full">
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <span class="text-xl font-semibold">
                    <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>
                </span>
            </div>
        </div>
    </header>
