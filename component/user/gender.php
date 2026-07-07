<div class="relative w-full max-w-sm" id="custom-select-container">
    <button type="button" id="select-button"
        class="peer w-full px-0 pt-5 pb-2 rounded-lg bg-white flex items-center justify-between text-left">
        <span id="selected-value" class="text-sm text-gray-900"><?= htmlspecialchars($user['user_sex']) ?></span>
        <svg id="select-arrow" class="w-4 h-4 text-gray-400 transition-transform duration-300" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
            </path>
        </svg>
    </button>

    <label class="absolute left-0 top-2 text-[10px] uppercase tracking-wider text-gray-500 pointer-events-none">
        Gender
    </label>

    <div id="select-dropdown"
        class="absolute top-full left-0 w-full mt-1 bg-white border border-gray-200 rounded-b-lg shadow-xl z-50 hidden">
        <ul class="py-1">
            <?php
            $sex = ["Male", "Female", "Other"];
            foreach ($sex as $gender):
                ?>
                <li class="px-4 py-2 text-sm hover:bg-gray-100 cursor-pointer" data-value=""><?= $gender ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>