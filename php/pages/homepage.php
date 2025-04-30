<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link rel="stylesheet" href="../../public/css/homepage.css">
</head>
<body>
    <!-- Header -->
    <?php include "../includes/header_homepage.php" ?>

    <!-- Features Section -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-12">Why Choose Gestion Budgétiaire?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h4 class="text-xl font-semibold mb-4">Easy Budget Tracking</h4>
                    <p class="text-gray-600">Monitor your income and expenses effortlessly with our intuitive interface.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h4 class="text-xl font-semibold mb-4">Detailed Reports</h4>
                    <p class="text-gray-600">Generate insightful reports to understand your spending habits.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h4 class="text-xl font-semibold mb-4">Secure & Private</h4>
                    <p class="text-gray-600">Your financial data is protected with top-notch security measures.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="bg-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">Gérez vos finances en toute simplicité</h2>
            <p class="text-lg text-gray-600 mb-8">Prenez le contrôle de votre budget avec Gestion Budgétaire. Suivez vos dépenses, planifiez vos économies et atteignez vos objectifs financiers.</p>
            <a href="#" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Commencer</a>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="bg-blue-600 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-3xl font-bold mb-4">Ready to Take Control?</h3>
            <p class="text-lg mb-8">Join thousands of users who trust Gestion Budgétiaire for their financial planning.</p>
            <a href="#" class="bg-white text-blue-600 px-6 py-3 rounded-lg hover:bg-gray-200">Sign Up Now</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2025 Gestion Budgétiaire. All rights reserved.</p>
            <div class="mt-4">
                <a href="#" class="text-gray-400 hover:text-white mx-2">Privacy Policy</a>
                <a href="#" class="text-gray-400 hover:text-white mx-2">Terms of Service</a>
                <a href="#" class="text-gray-400 hover:text-white mx-2">Contact Us</a>
            </div>
        </div>
    </footer>
</body>
</html>