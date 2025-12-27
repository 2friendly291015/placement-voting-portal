<?php
// Set timezone to IST (India Standard Time)
date_default_timezone_set('Asia/Kolkata');

// Configuration
$googleSheetsUrl = 'https://docs.google.com/spreadsheets/d/1hNWfcJB13I1rvraO0pPNaNre_mdvRPpfS6Ar6BoxWak/edit?usp=sharing';
$googleSheetsWebAppUrl = ''; // You'll need to set this up (see instructions below)

// Sanitize and validate input data
$name = filter_input(INPUT_POST, 'studentName', FILTER_SANITIZE_STRING) ?? '';
$regNo = filter_input(INPUT_POST, 'regNo', FILTER_SANITIZE_STRING) ?? '';
$pg_president = filter_input(INPUT_POST, 'pg_president', FILTER_SANITIZE_STRING) ?? '';
$pg_vp = filter_input(INPUT_POST, 'pg_vp', FILTER_SANITIZE_STRING) ?? '';
$ug_president = filter_input(INPUT_POST, 'ug_president', FILTER_SANITIZE_STRING) ?? '';
$ug_vp = filter_input(INPUT_POST, 'ug_vp', FILTER_SANITIZE_STRING) ?? '';
$timestamp = date('Y-m-d H:i:s');

// Validation
$errors = [];
if (empty($name)) $errors[] = 'Student name is required';
if (empty($regNo)) $errors[] = 'Registration number is required';
if (empty($pg_president)) $errors[] = 'PG President selection is required';
if (empty($pg_vp)) $errors[] = 'PG Vice President selection is required';
if (empty($ug_president)) $errors[] = 'UG President selection is required';
if (empty($ug_vp)) $errors[] = 'UG Vice President selection is required';

// Function to send data to Google Sheets
function sendToGoogleSheets($data) {
    // Method 1: Using Google Apps Script Web App (Recommended)
    $webAppUrl = 'https://script.google.com/macros/s/AKfycbxGhCfqD1deH-5vIS8rF9DKBTVlcbnP0Sh4hhDHPX2RcE0jfXhlLn8Kro219Niy25D-/exec'; // You need to create this
    
    if (empty($webAppUrl)) {
        return false; // Web App URL not configured
    }
    
    $postData = http_build_query([
        'name' => $data['name'],
        'regNo' => $data['regNo'],
        'pg_president' => $data['pg_president'],
        'pg_vp' => $data['pg_vp'],
        'ug_president' => $data['ug_president'],
        'ug_vp' => $data['ug_vp'],
        'timestamp' => $data['timestamp']
    ]);
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postData
        ]
    ]);
    
    $result = file_get_contents($webAppUrl, false, $context);
    return $result !== false;
}

// Alternative: Save to local CSV as backup
function saveToLocalCSV($data) {
    $csvFile = 'votes_backup.csv';
    $csvHeaders = ['Name', 'Register Number', 'PG President', 'PG Vice President', 'UG President', 'UG Vice President', 'Timestamp'];
    
    // Check if CSV file exists and create with headers if needed
    if (!file_exists($csvFile)) {
        $file = fopen($csvFile, 'w');
        if ($file) {
            fputcsv($file, $csvHeaders);
            fclose($file);
        }
    }
    
    // Append data
    $file = fopen($csvFile, 'a');
    if ($file) {
        $success = fputcsv($file, [
            $data['name'], 
            $data['regNo'], 
            $data['pg_president'], 
            $data['pg_vp'], 
            $data['ug_president'], 
            $data['ug_vp'], 
            $data['timestamp']
        ]);
        fclose($file);
        return $success;
    }
    return false;
}

// Save vote if no errors
$success = false;
$googleSheetsSuccess = false;
$localBackupSuccess = false;

if (empty($errors)) {
    $voteData = [
        'name' => $name,
        'regNo' => $regNo,
        'pg_president' => $pg_president,
        'pg_vp' => $pg_vp,
        'ug_president' => $ug_president,
        'ug_vp' => $ug_vp,
        'timestamp' => $timestamp
    ];
    
    // Try to send to Google Sheets
    $googleSheetsSuccess = sendToGoogleSheets($voteData);
    
    // Always save to local backup
    $localBackupSuccess = saveToLocalCSV($voteData);
    
    // Consider successful if either method works
    $success = $googleSheetsSuccess || $localBackupSuccess;
    
    if (!$success) {
        $errors[] = 'Unable to save vote. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $success ? 'Vote Submitted Successfully' : 'Vote Submission Error'; ?> - Placement Ambassadors Forum</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            line-height: 1.6;
            font-size: 16px;
        }

        .bg-container {
            position: relative;
            width: 100%;
            min-height: 100vh;
            background-image: url('Assets/background.jpg');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .bg-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%);
            z-index: 1;
        }

        .result-container {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 24px;
            text-align: center;
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            box-shadow: 
                0 30px 80px rgba(0, 0, 0, 0.25),
                0 15px 35px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.4);
            animation: slideUp 0.8s ease-out;
            max-height: 90vh;
            overflow-y: auto;
        }

        .result-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                <?php echo $success ? '#27ae60, #2ecc71, #27ae60' : '#e74c3c, #c0392b, #e74c3c'; ?>);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
            border-radius: 24px 24px 0 0;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .result-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 40px;
            animation: bounce 1s ease-out;
            <?php if ($success): ?>
                background: linear-gradient(135deg, #27ae60, #2ecc71);
                color: white;
                box-shadow: 0 15px 35px rgba(39, 174, 96, 0.3);
            <?php else: ?>
                background: linear-gradient(135deg, #e74c3c, #c0392b);
                color: white;
                box-shadow: 0 15px 35px rgba(231, 76, 60, 0.3);
            <?php endif; ?>
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0, 0, 0);
            }
            40%, 43% {
                transform: translate3d(0, -15px, 0);
            }
            70% {
                transform: translate3d(0, -8px, 0);
            }
            90% {
                transform: translate3d(0, -3px, 0);
            }
        }

        .result-title {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 15px;
            <?php if ($success): ?>
                color: #27ae60;
                background: linear-gradient(135deg, #27ae60, #2ecc71);
            <?php else: ?>
                color: #e74c3c;
                background: linear-gradient(135deg, #e74c3c, #c0392b);
            <?php endif; ?>
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .result-message {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 20px;
            line-height: 1.6;
            font-weight: 500;
        }

        .vote-details {
            background: rgba(248, 249, 250, 0.8);
            border-radius: 16px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
            text-align: left;
        }

        .vote-details h3 {
            color: #2c3e50;
            font-size: 18px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: 700;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 6px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            font-size: 14px;
        }

        .detail-label {
            font-weight: 600;
            color: #34495e;
            flex: 1;
        }

        .detail-value {
            font-weight: 500;
            color: #667eea;
            flex: 1;
            text-align: right;
        }

        .error-list {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid rgba(231, 76, 60, 0.3);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }

        .error-list h3 {
            color: #e74c3c;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .error-list ul {
            list-style: none;
            padding: 0;
        }

        .error-list li {
            color: #c0392b;
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }

        .error-list li::before {
            content: '⚠️';
            position: absolute;
            left: 0;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-width: 120px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
            box-shadow: 0 8px 25px rgba(149, 165, 166, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(149, 165, 166, 0.4);
        }

        .timestamp {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 15px;
            font-style: italic;
        }

        .footer-text {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            color: #7f8c8d;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .bg-container {
                background-attachment: scroll;
                padding: 15px;
            }
            
            .result-container {
                padding: 25px 20px;
                margin: 10px;
                max-width: 95%;
                max-height: 95vh;
            }
            
            .result-icon {
                width: 60px;
                height: 60px;
                font-size: 30px;
                margin-bottom: 15px;
            }
            
            .result-title {
                font-size: 22px;
            }
            
            .result-message {
                font-size: 14px;
            }
            
            .vote-details {
                padding: 15px;
            }
            
            .vote-details h3 {
                font-size: 16px;
            }
            
            .detail-row {
                flex-direction: column;
                gap: 3px;
                font-size: 13px;
            }
            
            .detail-value {
                text-align: left;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 8px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
                padding: 10px 20px;
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .result-container {
                padding: 20px 15px;
            }
            
            .result-title {
                font-size: 20px;
            }
            
            .detail-row {
                font-size: 12px;
            }
        }

        @media (max-height: 600px) {
            .result-container {
                padding: 20px;
                max-height: 95vh;
            }
            
            .result-icon {
                width: 60px;
                height: 60px;
                font-size: 30px;
                margin-bottom: 10px;
            }
            
            .result-title {
                font-size: 22px;
                margin-bottom: 10px;
            }
            
            .vote-details {
                padding: 15px;
                margin: 15px 0;
            }
        }
    </style>
</head>
<body>
    <div class="bg-container">
        <div class="result-container">
            <?php if ($success): ?>
                <div class="result-icon">✓</div>
                <h1 class="result-title">Vote Submitted Successfully!</h1>
                <p class="result-message">
                    Thank you, <strong><?php echo htmlspecialchars($name); ?></strong>!<br>
                    Your vote has been recorded and saved securely.
                </p>
                
                <div class="vote-details">
                    <h3>📋 Vote Summary</h3>
                    <div class="detail-row">
                        <span class="detail-label">Student Name:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($name); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Registration Number:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($regNo); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">PG President:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($pg_president); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">PG Vice President:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($pg_vp); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">UG President:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($ug_president); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">UG Vice President:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($ug_vp); ?></span>
                    </div>
                </div>
                
                <div class="timestamp">
                    📅 Submitted on: <?php echo date('F j, Y \a\t g:i A T'); ?>
                </div>
                
            <?php else: ?>
                <div class="result-icon">✗</div>
                <h1 class="result-title">Vote Submission Failed</h1>
                <p class="result-message">
                    We're sorry, but there was an issue processing your vote.<br>
                    Please review the errors below and try again.
                </p>
                
                <div class="error-list">
                    <h3>❌ Errors Found:</h3>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="action-buttons">
                <?php if ($success): ?>
                    <a href="index.html" class="btn btn-primary">
                        🏠 Return to Home
                    </a>
                <?php else: ?>
                    <a href="vote.html" class="btn btn-primary">
                        🔄 Try Again
                    </a>
                    <a href="index.html" class="btn btn-secondary">
                        🏠 Home
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="footer-text">
                <p><strong>Placement Ambassadors Forum</strong></p>
                <p>© 2025 All rights reserved</p>
            </div>
        </div>
    </div>

    <script>
        // Add confetti effect for successful submission
        <?php if ($success): ?>
        document.addEventListener('DOMContentLoaded', function() {
            // Create confetti particles
            function createConfetti() {
                const colors = ['#667eea', '#764ba2', '#27ae60', '#2ecc71', '#f39c12', '#e67e22'];
                
                for (let i = 0; i < 30; i++) {
                    setTimeout(() => {
                        const confetti = document.createElement('div');
                        confetti.style.cssText = `
                            position: fixed;
                            width: 8px;
                            height: 8px;
                            background: ${colors[Math.floor(Math.random() * colors.length)]};
                            left: ${Math.random() * 100}vw;
                            top: -10px;
                            border-radius: 50%;
                            pointer-events: none;
                            z-index: 1000;
                            animation: fall ${3 + Math.random() * 2}s linear forwards;
                        `;
                        
                        document.body.appendChild(confetti);
                        
                        setTimeout(() => {
                            confetti.remove();
                        }, 5000);
                    }, i * 100);
                }
            }
            
            // Add fall animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes fall {
                    from {
                        transform: translateY(-10px) rotate(0deg);
                        opacity: 1;
                    }
                    to {
                        transform: translateY(100vh) rotate(360deg);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
            
            // Trigger confetti
            setTimeout(createConfetti, 500);
        });
        <?php endif; ?>
        
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
