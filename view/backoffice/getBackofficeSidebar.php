<?php
function getBackofficeSidebar($root = "", $active = "") {

    // Define sidebar items
    $tabs = [
        "dashboard" => [
            "label" => "Dashboard",
            "link"  => "dashboard.php"
        ],
        
        "quiz" => [
            "label" => "Quiz",
            "link"  => "$root/views/backoffice/quiz/quiz-portal.php"
        ],

        "users" => [
            "label" => "Users",
            "link"  => "index1.php"   // ✔ Correction ici
        ],

        "orders" => [
            "label" => "Orders",
            "link"  => "#"
        ],
        "customers" => [
            "label" => "Customers",
            "link"  => "#"
        ],
        "reports" => [
            "label" => "Reports",
            "link"  => "#"
        ],
        "settings" => [
            "label" => "Settings",
            "link"  => "#"
        ],
    ];

    // Start building HTML
    $html = "
    <aside class='w-64 h-screen bg-[#00aff2] text-gray-100 flex flex-col'>
        <div class='p-6 text-2xl font-bold border-b border-gray-700'>
            Admin Panel
        </div>
        <nav class='flex-1 px-4 py-6 space-y-2'>
    ";

    // Loop through items
    foreach ($tabs as $key => $tab) {
        $isActive = ($active === $key);
        $activeClass = $isActive ? "bg-gray-700" : "hover:bg-gray-700";

        $html .= "
            <a href='{$tab['link']}' class='block px-4 py-2 rounded $activeClass'>{$tab['label']}</a>
        ";
    }

    // Add logout button
    $html .= "
        </nav>
        <div class='p-4 border-t border-gray-700'>
            <button class='w-full px-4 py-2 bg-[#40e0d0] rounded hover:bg-red-500'>Logout</button>
        </div>
    </aside>
    ";

    return $html;
}

?>
