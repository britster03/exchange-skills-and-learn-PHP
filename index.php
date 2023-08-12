<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Exchange Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-blue-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white text-xl font-semibold">Skill Exchange</a>
            <div>
                <a href="#" class="text-white mr-4">Home</a>
                <a href="#" class="text-white">Profile</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-gray-200 py-20">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-semibold mb-4">Welcome to Skill Exchange</h1>
            <p class="text-gray-600">Connect with others to share your expertise and learn something new.</p>
        </div>
    </header>

    <!-- PHP Content -->
    <?php
        // Sample dynamic content from PHP
        $skills = array(
            "Cooking Lessons" => "I can teach you how to cook delicious meals!",
            "Photography" => "Learn the art of photography from a professional."
        );
    ?>

    <!-- Skill Listings -->
    <section class="container mx-auto py-12">
        <h2 class="text-2xl font-semibold mb-6">Available Skills</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($skills as $skill => $description): ?>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-2"><?= $skill ?></h3>
                    <p class="text-gray-600 mb-4"><?= $description ?></p>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md">Send Request</button>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4">
        <p>&copy; <?= date("Y") ?> Skill Exchange Platform</p>
    </footer>

</body>
</html>
