<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <style>
        :root {
            --blue-600: #2563eb;
            --blue-500: #3b82f6;
            --blue-700: #1e40af
        }

        html,
        body {
            height: 100%;
            margin: 0
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, 'Helvetica Neu  e', Arial;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--blue-700) 0%, #60a5fa 100%);
        }

        .card {
            background: linear-gradient(180deg, rgba(37, 99, 235, 0.18), rgba(59, 130, 246, 0.12));
            backdrop-filter: blur(6px);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.4);
            width: 380px;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.12)
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px
        }

        .logo {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--blue-500), var(--blue-600));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white
        }

        h2 {
            margin: 0;
            font-size: 20px
        }

        .input {
            width: 100%;
            padding: 12px 16px;
            margin-bottom: 14px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #0f172a;
            height: 48px;
            box-sizing: border-box
        }

        .input::placeholder {
            color: #94a3b8
        }

        .input:focus {
            outline: none;
            border-color: var(--blue-600);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.14);
            background: #ffffff
        }

        .btn {
            background: var(--blue-700);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 10px;
            width: 100%;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.24);
            transition: box-shadow 180ms ease, background 120ms ease;
        }

        .btn:hover {
            /* shadow-only hover */
            box-shadow: 0 14px 36px rgba(2,6,23,0.18);
        }

        .btn:focus {
            outline: none;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.22);
        }

        .error {
            background: rgba(244, 63, 94, 0.15);
            color: #fecaca;
            padding: 8px;
            border-radius: 8px;
            margin-bottom: 12px;
            border: 1px solid rgba(244, 63, 94, 0.08)
        }

        .muted {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 12px
        }

        a.link {
            color: rgba(255, 255, 255, 0.95);
            text-decoration: none;
            font-weight: 600
        }
    </style>
</head>

<body>
    <div
        style="width: 100%; min-height: 100vh; background: linear-gradient(135deg, var(--blue-700) 0%, var(--blue-500) 100%); padding: 28px 20px;">
        <div style="max-width: 1400px; margin: 0 auto; position:relative;">
            <header style="text-align: center; margin-bottom: 8px; padding-top:8px;">
                <h1 id="mainTitle" style="color: white; font-size: 34px; margin-bottom: 6px; font-weight: 800;">Form
                    Patroli Keamanan</h1>
                <p id="companyName" style="color: rgba(255,255,255,0.95); font-size: 16px;">PT CBA Chemical Industry
                    Pabrik</p>
            </header>
            <div style="display:flex;align-items:center;justify-content:center;min-height:60vh;flex-direction:column;gap:12px;">
                <div class="card">
                    <h2 style="margin-top:0;margin-bottom:6px;text-align:center">LOGIN</h2>
                    <div class="muted" style="text-align:center;margin-bottom:10px;">Hanya admin yang bisa login.</div>
                    <br>
                    @if($errors->any())
                        <div class="error">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="/login">
                        @csrf
                        <input class="input" type="text" name="login" placeholder="Username" value="{{ old('login') }}"
                            required autocomplete="username" />
                        <input class="input" type="password" name="password" placeholder="Password" required
                            autocomplete="current-password" />
                        <div style="margin-top:8px"><button class="btn" type="submit">Masuk</button></div>
                    </form>
                </div>
                <br>
                <div style="text-align:center;">
                    <a href="/" class="btn" style="background: #60a5fa; display:inline-block; text-decoration:none; width:220px; padding:10px 16px;">Isi Data Patroli</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="site-footer" aria-hidden="false" style="position:fixed;left:0;right:0;bottom:12px;display:flex;justify-content:center;pointer-events:auto;">
        <div style="background: rgba(255,255,255,0.06); padding:8px 12px; border-radius:10px; color: rgba(255,255,255,0.92); font-size:13px;">
            &copy; {{ date('Y') }} PT CBA Chemical Industry | Team IT Pabrik.
        </div>
    </footer>
</body>
</html>