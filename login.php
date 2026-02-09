<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Staff - MyFilm</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-200 flex items-center justify-center h-screen">
    <div class="bg-slate-800 p-8 rounded-xl shadow-2xl border border-slate-700 w-full max-w-md">
        <h2 class="text-2xl font-bold text-yellow-500 mb-6 text-center">LOGIN STAFF</h2>
        
        <form action="proses_login.php" method="POST">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Username</label>
                <input type="text" name="username" required class="w-full bg-slate-900 border border-slate-600 p-2.5 rounded focus:border-yellow-500 outline-none">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Password</label>
                <input type="password" name="password" required class="w-full bg-slate-900 border border-slate-600 p-2.5 rounded focus:border-yellow-500 outline-none">
            </div>
            <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-bold py-2.5 rounded transition">
                Masuk Dashboard
            </button>
        </form>
        <p class="mt-4 text-center text-xs text-slate-500 italic">*Khusus Admin</p>
    </div>
</body>
</html>