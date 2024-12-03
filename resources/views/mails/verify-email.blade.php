<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        @import url('https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
    </style>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal"
    style="background-image: url('https://suitify.cloud/images/background/suitify_background_02.jpg'); background-size: cover; background-position: center;">

    <div class="flex justify-center items-center min-h-screen py-10 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md bg-white bg-opacity-90 rounded-lg shadow-lg overflow-hidden p-6 sm:p-10">

            <!-- Logo Section -->
            <div class="flex justify-center mb-4">
                <img src="https://suitify.cloud/images/logo/suitify_logo_dark.svg" alt="Suitify Logo"
                    class="h-12 w-auto">

            </div>

            <!-- Main Content -->
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Verify Your Email</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Thank you for signing up with Suitify! To complete the registration and activate your
                    account, please verify your email address by clicking the button below,
                </p>
            </div>

            <!-- Verify Button -->
            <div class="mt-8 text-center">
                <a href="{{ $url }}"
                    class="inline-block w-full px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-400 hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400">
                    Verify Email
                </a>
            </div>

            <!-- Footer Text -->
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>Verifying your email ensures
                    the security of your account and allows you to access all features available. If you did not sign up
                    for this account, you can safely ignore this message. We’re excited to have you on board!.</p>
                <p class="mt-4">Thanks,<br>Suitify Team</p>
            </div>
        </div>
    </div>

</body>

</html>
