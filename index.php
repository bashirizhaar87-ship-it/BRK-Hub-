<?php
// Path: BRK-Hub/index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRK Hub - Ultimate Downloader</title>
    <!-- Custom Dark Theme CSS Link -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .box {
            background: #1e1e1e;
            padding: 35px;
            border-radius: 15px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 4px 20px rgba(13, 110, 253, 0.2);
            border: 1px solid #333;
            text-align: center;
        }
        select, input, button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border-radius: 30px;
            border: 1px solid #0d6efd;
            box-sizing: border-box;
            font-size: 14px;
        }
        select, input {
            background: #121212;
            color: white;
        }
        button {
            background: #0d6efd;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: 0.3s;
        }
        button:hover {
            background: #0b5ed7;
            box-shadow: 0 0 15px rgba(13, 110, 253, 0.5);
        }
        .result {
            margin-top: 20px;
            padding: 12px;
            background: #121212;
            border-radius: 10px;
            display: none;
            font-size: 14px;
            border: 1px solid #333;
        }
        .result a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>📥 BRK Hub Downloader</h2>
    <p class="lead" style="font-size: 14px; color: #bdbdbd;">Download videos from YouTube, TikTok, FB & Insta</p>

    <form id="downloadForm">
        <select id="platform">
            <option value="auto">Auto Detect Platform</option>
            <option value="youtube">YouTube</option>
            <option value="instagram">Instagram</option>
            <option value="facebook">Facebook</option>
            <option value="tiktok">TikTok</option>
        </select>

        <input type="url" id="url" placeholder="Paste video link here..." required>
        <button type="submit">Process Video</button>
    </form>

    <div class="result" id="resultBox"></div>
</div>

<script>
document.getElementById("downloadForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let url = document.getElementById("url").value;
    let platform = document.getElementById("platform").value;
    let resultBox = document.getElementById("resultBox");

    resultBox.style.display = "block";
    resultBox.style.color = "#ffffff";
    resultBox.innerHTML = "⚡ Extracting download links... Please wait.";

    // Naya API path call ho raha hai yahan
    fetch("api/download.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "url=" + encodeURIComponent(url) + "&platform=" + encodeURIComponent(platform)
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === "success") {
            resultBox.innerHTML = "✅ <strong>Success!</strong> Your file is ready:<br><br><a href='" + data.download_url + "' target='_blank' style='background: #0d6efd; color: white; padding: 8px 15px; border-radius: 20px; display: inline-block; margin-top: 5px;'>Download Now</a>";
        } else {
            resultBox.style.color = "#ef4444";
            resultBox.innerHTML = "❌ " + data.message;
        }
    })
    .catch(err => {
        resultBox.style.color = "#ef4444";
        resultBox.innerHTML = "❌ Connection error or invalid response from server.";
    });
});
</script>

</body>
</html>
