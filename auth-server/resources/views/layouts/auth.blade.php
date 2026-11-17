<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | WebGIS LP2B Bogor</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            color: #17352b;
            background: #edf4ef;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 24px;
            background:
                radial-gradient(ellipse at 12% 12%, rgba(116, 172, 119, .23), transparent 35%),
                linear-gradient(145deg, #f7faf6, #e6f0e8);
        }

        .card {
            width: min(100%, 430px);
            padding: 38px;
            border: 1px solid #e1ebe2;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 20px 60px rgba(30, 65, 43, .12);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
        }

        .brand-mark {
            display: grid;
            width: 46px;
            height: 46px;
            place-items: center;
            border-radius: 14px;
            color: #fff;
            background: #27734c;
            font-size: 23px;
        }

        .brand strong, .brand span { display: block; }
        .brand strong { font-size: 15px; }
        .brand span { margin-top: 3px; color: #6a7d71; font-size: 12px; }
        h1 { margin: 0 0 8px; font-size: 27px; }
        .intro { margin: 0 0 26px; color: #718078; line-height: 1.5; }
        .field { margin: 0 0 17px; }
        label { display: block; margin-bottom: 7px; font-size: 13px; font-weight: 650; }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            min-height: 46px;
            padding: 11px 13px;
            border: 1px solid #d5e0d7;
            border-radius: 9px;
            outline: none;
            font: inherit;
        }

        input:focus { border-color: #32835a; box-shadow: 0 0 0 3px #32835a1c; }
        .remember { display: flex; align-items: center; gap: 8px; margin: 2px 0 20px; color: #57685e; font-size: 13px; }
        .remember input { accent-color: #27734c; }

        button {
            width: 100%;
            min-height: 47px;
            border: 0;
            border-radius: 9px;
            color: #fff;
            background: #27734c;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
        }

        button:hover { background: #1d5e3d; }
        .switch { margin: 22px 0 0; color: #718078; text-align: center; font-size: 13px; }
        a { color: #27734c; font-weight: 650; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .error { margin: 6px 0 0; color: #b42318; font-size: 12px; }
        .form-error { margin: 0 0 18px; padding: 11px 13px; border-radius: 8px; background: #fff1f0; color: #a32119; font-size: 13px; }

        @media (max-width: 480px) {
            .card { padding: 28px 22px; }
        }
    </style>
</head>
<body>
    <main class="card">
        <div class="brand">
            <div class="brand-mark" aria-hidden="true">🍀</div>
            <div>
                <strong>WEBGIS LP2B</strong>
                <span>Kabupaten Bogor</span>
            </div>
        </div>
        @yield('content')
    </main>
</body>
</html>
